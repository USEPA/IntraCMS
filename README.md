# IntraCMS

<img src="https://user-images.githubusercontent.com/2470152/192600667-deff1564-29fb-4134-86fa-01a66b4b7d43.png" data-canonical-src="https://user-images.githubusercontent.com/2470152/192600667-deff1564-29fb-4134-86fa-01a66b4b7d43.png" width="600" />

This is the EPA's intranet site which is built on Drupal 9 and includes a huge number of patches and custom modules. [Check out them out here](https://github.com/USEPA/IntraCMS/tree/development/docroot/modules/custom)


Basic Installation
==================

**More detailed setup instructions in [INSTALLATION](INSTALLATION.md)**

Using [DDEV](https://ddev.readthedocs.io/)

1. From command line, run command  `git clone https://github.com/USEPA/IntraCMS.git`
2. `cd IntraCMS` -- Navigate into the cloned git repo 
3. `ddev config --project-type=drupal9 --docroot=docroot --create-docroot` 
4. `ddev start`
5. If necesary, fix any port conflicts. See Fix port conflicts by configuring your project to use different ports: https://ddev.readthedocs.io/en/latest/users/basics/troubleshooting/#method-2-fix-port-conflicts-by-configuring-your-project-to-use-different-ports
6. If not already installed, run `ddev composer require "drush/drush"`
7. Update the config.yaml file located in the .ddev directory:
    1. Make sure the docroot is set correctly: `docroot: docroot`
    2. Update to the appropriate php version - currently 7.3: `php_version: "7.3"`
    3. Set the correct composer version - currently 1: `composer_version: "1"`
8. `ddev composer site-install` Until the project installation profile transitions to minimal from standard, there will always be errors related to the shortcut menu. The install script removes existing shortcuts, sets the site UUID, enables config_split, and runs a `drush-cim`. The post install script removes web.config and install.php.
9. `ddev launch'`



DISCLAIMER
==========

The United States Environmental Protection Agency (EPA) GitHub project code is provided on an "as is" basis and the user assumes responsibility for its use.  EPA has relinquished control of the information and no longer has responsibility to protect the integrity , confidentiality, or availability of the information.  Any reference to specific commercial products, processes, or services by service mark, trademark, manufacturer, or otherwise, does not constitute or imply their endorsement, recommendation or favoring by EPA.  The EPA seal and logo shall not be used in any manner to imply endorsement of any commercial product or activity by EPA or the United States Government.
