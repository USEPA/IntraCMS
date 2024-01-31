FROM registry.epa.gov/intranet-cms/intranet-cms-infra/intracms-php7.4.33:latest
SHELL ["/bin/bash", "-c"]

WORKDIR /app 

COPY . .

RUN composer clearcache
RUN composer install --no-interaction
RUN ./docker/scripts/bower.sh
RUN rsync -avz --recursive --links --times --executability --delete --delete-after --exclude=docker --exclude=k8s --exclude=*web.config --exclude=docroot/sites/*/settings.php --exclude=docroot/sites/*/settings.local.php --exclude=docroot/sites/*/services.yml --exclude=docroot/tools --exclude=docroot/sites/*/files --exclude=vendor/simplesamlphp/simplesamlphp/config --exclude=vendor/simplesamlphp/simplesamlphp/metadata --exclude=vendor/simplesamlphp/simplesamlphp/cert --delay-updates * /srv/drupal-root
RUN ln -s /srv/drupal-root/vendor/drush/drush/drush /usr/bin/drush 
RUN ln -s /public/data/drupal-files /srv/drupal-root/docroot/sites/default/files 
RUN ln -s /public/server/maint/default/settings.php /srv/drupal-root/docroot/sites/default/settings.php 
RUN ln -s /public/server/maint/default/settings.local.php /srv/drupal-root/docroot/sites/default/settings.local.php
RUN ln -s /public/server/maint/simplesamlphp/config /srv/drupal-root/vendor/simplesamlphp/simplesamlphp/config
RUN ln -s /public/server/maint/simplesamlphp/metadata /srv/drupal-root/vendor/simplesamlphp/simplesamlphp/metadata
RUN ln -s /public/server/maint/simplesamlphp/cert /srv/drupal-root/vendor/simplesamlphp/simplesamlphp/cert
RUN ln -s /srv/drupal-root/vendor/simplesamlphp/simplesamlphp/www /srv/drupal-root/docroot/simplesaml
RUN ln -s /public/server/maint/drush-files/intra/ /srv/drupal-root/drush

RUN rm -rf /app/*

ENTRYPOINT ["/etc/entrypoint.sh"]

EXPOSE 80 443 9000

