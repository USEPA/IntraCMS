# Architecture & Developer Guide

This document provides detailed information about the repository structure, Drupal architecture, application components, and operational procedures for the EPA Intranet CMS application.

## Repository Overview

This is the EPA IntracMS repository - a Drupal 10 intranet site based on the U.S. Web Design System (USWDS). It provides a comprehensive content management system for EPA's internal communications and collaboration needs.

**Key Technologies**: Drupal 10.4.1, PHP 8.3, USWDS Base Theme, Lightning Distribution, SimpleSAMLphp, deployed on AWS EKS with Fargate.

## Related Repositories

**This application repository works in tandem with the intranet-cms-infra infrastructure repository.**

### Repository Relationship

```
┌─────────────────────────────────┐
│   intranet-cms-infra          │
│   Infrastructure as Code        │
│                                 │
│   • Creates EKS clusters        │
│   • Provisions namespaces       │
│   • Manages AWS resources       │
│   • Builds PHP base image       │
└────────────┬────────────────────┘
             │ Provides infrastructure
             ↓
┌─────────────────────────────────┐
│   IntraCMS (THIS)               │
│   Application Code              │
│                                 │
│   • Drupal 10 application       │
│   • Deploys to namespaces       │
│   • Uses S3/EFS resources       │
│   • Builds app container        │
└─────────────────────────────────┘
```

### Infrastructure Repository (intranet-cms-infra)
**Location**: `~/Repositories/intranet-cms-infra`

**What it provides**:
- EKS clusters: `cms-shared-dev00` (dev/dev10) and `cms-shared-dev01` (stage)
- Kubernetes namespaces: `cms-45-dev`, `cms-45-next`, `cms-45-stage`
- AWS S3 bucket: `intranet-cms-dev` with IAM roles for pod access (IRSA)
- EFS file systems with access points for Drupal files
- ElastiCache Memcached cluster for Drupal caching
- GitLab Kubernetes agents for CI/CD deployment
- PHP 8.3 base container image with NGINX

### Application Repository (IntraCMS)
**Location**: `~/Repositories/IntraCMS` (THIS REPOSITORY)

**What it contains**:
- Drupal 10.4.1 application code and configuration
- Custom EPA modules (epa_core, epa_media, epa_wysiwyg, etc.)
- Custom EPA themes (epa_intranet, epa_intranet_2)
- Kubernetes deployment manifests in `k8s/` directory
- Container build configuration in `docker/` directory
- Configuration management in `config/` directory

### Integration Points

1. **Deployment Targets**:
   - This repo deploys to namespaces created by infrastructure repo
   - `dev-container` branch → `cms-45-dev` namespace
   - `dev-container-10` branch → `cms-45-next` namespace
   - `stage-container-1` branch → `cms-45-stage` namespace

2. **GitLab Agents**:
   - CI/CD uses agents deployed by infrastructure repo
   - Dev/Dev10: `kubectl config use-context intranet-cms/intracms-infra-dev:intranetcms-dev-k8s-agent`
   - Stage: `kubectl config use-context intranet-cms/cms-k8s-stg-resources:intranetcms-stage-k8s-agent`

3. **Container Images**:
   - Infrastructure provides: `registry.epa.gov/intranet-cms/intracms-infra/intracms-php` (PHP base)
   - This repo builds: `registry.epa.gov/intranet-cms/intracms/intranetcms-build` (Drupal app)

4. **Storage**:
   - Drupal files mount EFS volumes provided by infrastructure (PVC: `intranetcms-efs-pvc`)
   - Drupal file storage uses S3 bucket with IRSA service account `oms-shell-sa`
   - Mount point: `/public` for Drupal public files

5. **Caching**:
   - Memcached endpoints provisioned by infrastructure repo
   - Retrieved from AWS SSM Parameter Store: `/intranetcms/dev/endpoints/memcached`
   - Configure in Drupal's `settings.php` for Memcache module

6. **Networking**:
   - NGINX ingress controllers provisioned by infrastructure
   - This repo defines ingress rules in `k8s/intranetcms.yml`, `k8s/intranetcms-stage.yml`
   - URLs: `dev.intranetcms-dev.aws.epa.gov`, `stage.intranetcms-stage.aws.epa.gov`

### PHP Version Management

PHP runtime and Composer dependency resolution are controlled across **both** repositories and must be kept in sync:

- **Runtime (intranet-cms-infra)** - `docker/php/Dockerfile` sets the PHP version via `FROM php:8.3-fpm-alpine3.22` and `ENV PHP_VERSION=8.3`, which builds the `intracms-php` base image consumed by this repo's `docker/Dockerfile`.
- **Dependency resolution (this repo)** - `composer.json` pins `config.platform.php` and enforces `require.php` to the same version, so Composer resolves packages against the deployed runtime instead of the local/live PHP.

To upgrade PHP (for example, 8.3 to 8.4):

1. In `intranet-cms-infra`, edit `docker/php/Dockerfile`: bump the `FROM php:<version>-fpm-alpineX.Y` tag and `ENV PHP_VERSION=<version>`, then rebuild and publish the `intracms-php` base image.
2. In this repo, edit `composer.json`: set `config.platform.php` to `<version>` and `require.php` to `>=<version>`.
3. Run `composer update` to re-resolve and regenerate `composer.lock` against the new platform, then rebuild the app image.

### When to Work in Each Repository

**Work in intranet-cms-infra when**:
- Scaling cluster capacity or changing instance types
- Adding new AWS services (RDS, ElastiCache, etc.)
- Modifying IAM permissions or security groups
- Changing namespace quotas or Fargate profiles
- Updating base PHP/NGINX configuration
- Adding new environments or clusters

**Work in IntraCMS (this repo) when**:
- Updating Drupal core, modules, or themes
- Developing custom EPA functionality
- Changing site configuration or content types
- Modifying deployment replicas or resource requests
- Updating application environment variables
- Working on Drupal features, content, or design

## Development Environment Setup

### Prerequisites
- DDEV installed (recommended) or PHP 8.3, Composer 2.x, MySQL/MariaDB
- Git and Git Bash (Windows)
- Docker for containerized development

### DDEV Setup (Recommended)
```bash
# Clone repository
git clone https://github.com/USEPA/IntraCMS.git
cd IntraCMS

# Configure DDEV
ddev config --project-type=drupal9 --docroot=docroot --create-docroot
ddev start

# Install dependencies and site
ddev composer install
ddev composer site-install

# Launch site
ddev launch
```

### Manual Configuration for DDEV
Update `.ddev/config.yaml`:
```yaml
docroot: docroot
php_version: "8.3"
composer_version: "2"
```

## Common Development Commands

### Site Installation and Setup
```bash
# Full site installation (automated)
ddev composer site-install

# Manual site installation steps
ddev drush site-install standard -n --ansi
ddev drush entity:delete shortcut -y
ddev drush cset system.site uuid a20f8b2d-8c57-4965-bb1c-d142c5b66431 -y
ddev drush cr
ddev drush en config_split -y
ddev drush cim -y
```

### Configuration Management
```bash
# Export configuration
ddev drush config:export
# or
ddev drush cex

# Import configuration
ddev drush config:import
# or
ddev drush cim

# Preview configuration changes
ddev drush config:import --preview
```

### Database Operations
```bash
# Clear cache
ddev drush cr

# Update database
ddev drush updb -y

# Check status
ddev drush status
```

### Development Tools
```bash
# Install Drush (if needed)
ddev composer require drush/drush

# Enable development modules
ddev drush en devel -y

# Reset user password
ddev drush upwd admin --password="admin"
```

### Container Development
```bash
# Build container image
docker build -f docker/Dockerfile -t intracms:local .

# Clean up containers and volumes
docker system prune -f
docker volume prune -f
```

## High-Level Architecture & Structure

### Core Technology Stack
- **Drupal 10.4.1** - Content management system
- **USWDS Base Theme** - U.S. Web Design System compliance
- **Lightning Distribution** - Accelerated Drupal development
- **Group Module** - Content and user segmentation
- **Kubernetes** - Container orchestration on AWS EKS
- **SimpleSAMLphp** - Single Sign-On authentication

### Directory Structure
```
intracms/
├── config/                 # Configuration management
│   ├── sync/              # Base configurations  
│   ├── dev/               # Development overrides
│   ├── staging/           # Staging overrides
│   └── production/        # Production overrides
├── docker/                # Container configuration
├── docroot/               # Drupal web root
│   ├── modules/custom/    # Custom EPA modules
│   ├── themes/custom/     # Custom EPA themes
│   └── sites/default/     # Site configuration
├── k8s/                   # Kubernetes manifests
├── patches/               # Composer patches
└── post_deployment_files/ # Deployment scripts
```

### Custom Modules
- **epa_core** - Core EPA functionality
- **epa_media** - Media management enhancements
- **epa_wysiwyg** - WYSIWYG editor customizations
- **group_media_context** - Group-aware media handling
- **content_migrations** - Data migration utilities
- **webform_custom_submissions** - Enhanced webform handling

### Custom Themes
- **epa_intranet** - Primary EPA intranet theme (USWDS-based)
- **epa_intranet_2** - Secondary theme variant

### Architecture Flow
```mermaid
graph TD
    A[Content Editors] --> B[Drupal 10 CMS]
    B --> C[USWDS Theme Layer]
    B --> D[Group Module]
    B --> E[Custom EPA Modules]
    F[SimpleSAMLphp] --> B
    B --> G[Kubernetes Pods]
    G --> H[AWS EKS]
    B --> I[Config Splits]
    I --> J[Environment-Specific Settings]
```

## Key Technologies & Integrations

### Authentication
- **SimpleSAMLphp** for SSO integration with EPA identity systems
- Configuration managed through mounted volumes in containers

### Content Management
- **Paragraphs** for structured content creation
- **Entity Browser** for media selection
- **Webforms** for data collection
- **Group module** for content organization and access control

### Media Management
- Custom EPA media modules for enhanced file handling
- Image cropping and optimization
- Video embedding capabilities

### Notable Contrib Modules
- **Lightning Core/Media/Workflow** - Drupal distribution components
- **USWDS** - U.S. Web Design System integration
- **Paragraphs** - Structured content components
- **Group** - Multi-site content organization
- **Webform** - Form builder and submissions
- **FullCalendar** - Event management
- **Search API** - Enhanced search functionality

### Critical Patches Applied
The system applies numerous patches for Drupal 10 compatibility and enhanced functionality:
- Group module enhancements for content transitions and media library access
- CKEditor 5 integration fixes
- Lightning distribution compatibility updates
- Performance optimizations for entity queries

## Container Deployment & Kubernetes

### Image Build Process
```bash
# GitLab CI builds images using Kaniko
/kaniko/executor --context $CI_PROJECT_DIR \
  --dockerfile $CI_PROJECT_DIR/docker/Dockerfile \
  --destination $CI_REGISTRY_IMAGE/intranetcms-build:$CI_COMMIT_SHORT_SHA
```

### Kubernetes Deployment
The application deploys across multiple environments:
- **dev** - Development environment
- **dev10** - Drupal 10 testing environment  
- **stage** - Staging environment

### Key Kubernetes Resources
```yaml
# Deployment with Fargate compute
apiVersion: apps/v1
kind: Deployment
metadata:
  name: intranetcms
  annotations:
    eks.amazonaws.com/compute-type: fargate
```

### Useful kubectl Commands
```bash
# Check deployment status
kubectl get pods -n cms-45-dev

# View logs
kubectl logs deployment/intranetcms -n cms-45-dev

# Execute drush commands
kubectl exec -it deployment/intranetcms -n cms-45-dev -- drush status
```

### Post-Deployment Tasks
Automated via Kubernetes jobs:
```bash
# Database updates, cache clear, config import
drush @intra updb --yes && drush @intra cr && drush @intra cim --yes
```

## GitLab CI/CD Pipeline

### Pipeline Stages
1. **build** - Container image creation with Kaniko
2. **prisma scan** - Security vulnerability scanning
3. **Non-Production Deployments** - Dev, Stage, Dev10 environments
4. **Post Deploy** - Drush commands for database updates
5. **dast** - Dynamic Application Security Testing (manual)

### Branch Strategy
- `dev-container` → Development deployment
- `stage-container-1` → Staging deployment  
- `dev-container-10` → Drupal 10 development

### Security Scanning
- **Prisma Cloud** integration for container vulnerability assessment
- **DAST** (Dynamic Application Security Testing) with ZAP scanner
- Automated security checks in CI/CD pipeline

### Environment Variables
```yaml
DAST_WEBSITE: "https://stage.intranetcms-stage.aws.epa.gov/"
WEB_HTTPS: "false"
WEB_HTTPS_ONLY: "false"
```

## Configuration Management Strategy

### Config Split Implementation
The site uses Drupal's Configuration Split module to manage environment-specific settings:
- **Base config** in `config/sync/`
- **Development overrides** in `config/dev/`
- **Staging overrides** in `config/staging/`
- **Production overrides** in `config/production/`

### Site UUID
Fixed UUID for configuration synchronization:
```bash
a20f8b2d-8c57-4965-bb1c-d142c5b66431
```

## Development Workflow

### Feature Development Process
1. Create feature branch from `development`
2. Set up local environment with DDEV
3. Make changes and test locally
4. Export configuration changes: `ddev drush cex`
5. Commit and push changes
6. Create pull request to `development`
7. Deploy to development environment for testing

### Code Standards
- Follow Drupal coding standards
- Custom modules use EPA namespace
- Themes extend USWDS base theme
- All configuration managed through code

## Troubleshooting

### Common Issues
- **Config Import Errors**: Ensure site UUID matches: `drush config-set "system.site" uuid "a20f8b2d-8c57-4965-bb1c-d142c5b66431"`
- **File Permissions**: Set temp directory: `$settings['file_temp_path'] = 'sites/default/files/temp';`
- **Memory Issues**: Increase PHP memory limit in php.ini: `memory_limit = 2048M`

### DDEV Troubleshooting  
- Port conflicts: Configure alternative ports in `.ddev/config.yaml`
- Container issues: `ddev restart` or `ddev poweroff && ddev start`
- Database issues: `ddev drush sql:sync` from working environment

## Additional Resources

- **Installation Guide**: [INSTALLATION.md](INSTALLATION.md)
- **Contributing Guide**: [contributing.md](contributing.md)
- **System Requirements**: [system-requirements.md](system-requirements.md)
- **EPA Open Source Guidance**: https://www.epa.gov/developers/open-source-software-and-epa-code-repository-requirements