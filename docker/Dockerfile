# Base image is the PHP 8.3 + NGINX image built by the intranet-cms-infra repo.
# PHP_BASE_TAG lets CI pin an immutable tag (see .gitlab-ci.yml --build-arg);
# it defaults to 'latest' so local/manual builds keep working.
ARG PHP_BASE_TAG=latest
FROM registry.epa.gov/intranet-cms/intranet-cms-infra/intracms-php:${PHP_BASE_TAG}
SHELL ["/bin/bash", "-c"]

WORKDIR /app

# Dependency layer: copy only the Composer manifests and local patches first so
# this expensive step (package downloads + patch fetches) is cached by Kaniko
# across commits that do not change dependencies.
COPY composer.json composer.lock ./
COPY patches/ ./patches/
# --prefer-dist avoids slow git clones; --optimize-autoloader speeds runtime
# class loading; --no-dev drops dev-only deps (drush/devel live in `require`, so
# they remain); --no-progress keeps the build log readable.
RUN composer install --no-interaction --no-progress --prefer-dist --optimize-autoloader --no-dev

# Application layer: bring in the rest of the source on top of the installed deps.
COPY . .

# Copy bower-managed front-end libraries into docroot/libraries.
RUN ./docker/scripts/bower.sh
# Publish the built app to /srv/drupal-root, excluding env-specific and secret
# files (settings*.php, services.yml, SimpleSAMLphp config/metadata/cert) that
# are supplied at runtime from the EFS-mounted /public volume, not baked in.
RUN rsync -avz --recursive --links --times --executability --delete --delete-after --exclude=docker --exclude=k8s --exclude=*web.config --exclude=docroot/sites/*/settings.php --exclude=docroot/sites/*/settings.local.php --exclude=docroot/sites/*/services.yml --exclude=docroot/tools --exclude=docroot/sites/*/files --exclude=vendor/simplesamlphp/simplesamlphp/config --exclude=vendor/simplesamlphp/simplesamlphp/metadata --exclude=vendor/simplesamlphp/simplesamlphp/cert --delay-updates * /srv/drupal-root
# Symlink runtime config/data (drush alias+binary, files, settings, SimpleSAMLphp)
# from the EFS-mounted /public volume into the Drupal root.
RUN ln -s /srv/drupal-root/vendor/drush/drush/drush /usr/bin/drush 
RUN ln -s /public/data/drupal-files /srv/drupal-root/docroot/sites/default/files 
RUN ln -s /public/server/maint/default/settings.php /srv/drupal-root/docroot/sites/default/settings.php 
RUN ln -s /public/server/maint/default/settings.local.php /srv/drupal-root/docroot/sites/default/settings.local.php
RUN ln -s /public/server/maint/simplesamlphp/config /srv/drupal-root/vendor/simplesamlphp/simplesamlphp/config
RUN ln -s /public/server/maint/simplesamlphp/metadata /srv/drupal-root/vendor/simplesamlphp/simplesamlphp/metadata
RUN ln -s /public/server/maint/simplesamlphp/cert /srv/drupal-root/vendor/simplesamlphp/simplesamlphp/cert
RUN ln -s /srv/drupal-root/vendor/simplesamlphp/simplesamlphp/public /srv/drupal-root/docroot/simplesaml
RUN ln -s /public/server/maint/drush-files/intra/ /srv/drupal-root/drush
RUN ln -s /srv/drupal-root/vendor/drush/drush/drush.php /usr/bin/drush.php

# Drop the build context now that the app has been published to /srv/drupal-root.
RUN rm -rf /app/*

ENTRYPOINT ["/etc/entrypoint.sh"]

EXPOSE 80 443 9000

