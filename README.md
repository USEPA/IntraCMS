# IntraCMS

The United States Environmental Protection Agency (EPA) GitHub project code is provided on an "as is" basis and the user assumes responsibility for its use.  EPA has relinquished control of the information and no longer has responsibility to protect the integrity , confidentiality, or availability of the information.  Any reference to specific commercial products, processes, or services by service mark, trademark, manufacturer, or otherwise, does not constitute or imply their endorsement, recommendation or favoring by EPA.  The EPA seal and logo shall not be used in any manner to imply endorsement of any commercial product or activity by EPA or the United States Government.

Required Software & Setup
=========================

### PHP

-   Download the latest version of PHP 7 (currently 7.2.24) - <https://windows.php.net/download>.
-   Add the PHP directory to the system environment PATH variable (typically done manually).
-   Enter `php --v` in the command line (cmd) to confirm PHP is properly installed.
-   Open the `php.ini` file in the PHP directory using a text editor and make the following changes:

    #### Resource Limits

    -   Set `memory_limit = 2048M`.

    #### Dynamic Extensions

    -   Enable (remove leading semi-colon) `extension=gd2`.
    -   Enable `extension=pdo_mysql`.
    -   Enable `extension=pdo_pgsql`.
    -   Enable `extension=pdo_sqlite`.

    #### Paths and Directories

    -   Add `zend_extension = php_opcache.dll`.

    #### Module Settings

    -   Enable (remove leading semi-colon) `opcache.enable=1`.
    -   Enable `opcache.enable_cli=0`.
    -   Enable `opcache.memory_consumption=128`.
    -   Enable `opcache.interned_strings_buffer=8`.
    -   Enable `opcache.max_accelerated_files=4000`.
    -   Enable `opcache.revalidate_freq=60`.
    -   Enable `opcache.file_cache_only=0`.
    -   Add `opcache.fast_shutdown=1`.

### Composer

-   Download and install the latest version of Composer (v1.10.13) - <https://getcomposer.org/download>.
-   Add the Composer directory to the system environment PATH variable (typically occurs automatically during installation).
-   Enter `composer --v` in the command line (cmd) to confirm Composer is properly installed.

### Git / Git Bash

-   Download and install the latest version of Git - <https://git-scm.com/downloads>.
-   Add the Git directory to the system environment PATH variable (typically occurs automatically during installation).
-   Enter `git ---version` in the command line (cmd) to confirm Git is properly installed.

### Drush

-   Using Git Bash, enter `git clone <https://github.com/drush-ops/drush.git>` to clone Drush into your current directory.
-   Enter `cd drush` to navigate in to the cloned drush repo/folder.
-   Enter `composer install` to install/update the project dependencies.
-   Enter `chmod u+x drush.bat` to make the file executable as a program by the owner.
-   Add the Drush directory to the system environment PATH variable (typically done manually).
-   Enter `drush status` in the command line (cmd) to confirm Drush is properly installed.

### PostgreSQL

-   Download and install the latest version of PostgreSQL - <https://www.enterprisedb.com/downloads/postgres-postgresql-downloads>.
-   During installation, use the default settings and make sure to install the PG extension.
-   Open the application and create a new connection to the database server.
-   Expand the Objects section, then right click on Databases and select to Add a New Database.


### IIS Express

-   Download and install the latest version of IIS Express - <https://www.microsoft.com/en-us/download/details.aspx?id=48264>.
-   Add the IIS Express directory to the system environment PATH variable (typically done manually).
-   Enter `iisexpress /?` in the command line (cmd) to confirm IIS Express is properly installed.

Cloning Repository & Installing Dependencies
============================================

-   Using Git Bash, enter `git clone <https://github.com/USEPA/IntraCMS.git>` to clone the IntraCMS project into your current directory.
-   If required, enter you Git username/email and password when prompted.
-   Enter `cd IntraCMS` to navigate in to the cloned IntraCMS repo/folder.
-   Enter `git checkout -b origin/development` to checkout the development branch.
-   Enter `composer install` to install/update the project dependencies.

Configuring & Starting Web-Server
=================================

-   Open the `applicationhost.config` file in the `C:\Users\[USER]\Documents\IISExpress\config` directory using a text editor and make the following changes:

    #### Sites
    ```xml
    <site name="[site_name]" id="1" serverAutoStart="true">
       <application path="/" >
           <virtualDirectory path="/" physicalPath="[path to project directory]\docroot" />
       </application>
       <bindings>
           <binding protocol="http" bindingInformation=":8080:localhost" />
       </bindings>
    </site>
    ```
    

    #### Fast CGI
    ```xml
    <fastCgi>		
            <application fullPath="[path to PHP directory]\php-cgi.exe" stderrMode="ReturnStdErrIn500" activityTimeout="370">		
                <environmentVariables>		
                    <environmentVariable name="PHPRC" value="[path to PHP directory]" />		
                </environmentVariables>		
            </application>		
        </fastCgi>
    ```
     


    #### Handlers
    ```xml
    <add name="FastCGI" path="*.php" verb="GET,HEAD,POST,OPTIONS,PUT,DELETE,PATCH" modules="FastCgiModule" scriptProcessor="[path to PHP directory]\php-cgi.exe" resourceType="File" requireAccess="Script" />
    ```
    
    

-   To start the web server, run `iisexpress` in the command line. If you are managing multiple sites within the `applicationhost.config` file, run `iisexpress /site:[site_name]` or `iisexpress /site:[site_id]`
-   Navigate to `<http://localhost:8080/>`.

Installing Drupal
=================

-   Select the `English` language when prompted.
-   Select the `Lightning` installation profile when prompted.
-   Select the `SQLite` database when prompted.
-   Enter the site name, email address, username & password when prompted.

Importing Drupal Configurations
===============================

### Update Sync & Temp Folder Locations

-   Open the `settings.php` file in the `IntraCMS\docroot\sites\default` directory using a text editor and make the following change:
    -   Set `$settings['config_sync_directory'] = '../config/sync';`.
    -   Set `$settings['file_temp_path'] = './files/temp';`.
-   Create a folder called `temp` in the `IntraCMS\docroot\sites\default\files` directory.

### Set Site UUID & Import Configurations

-   Using Git Bash, navigate to the IntraCMS cloned repo/folder.
-   Enter `drush config-set "system.site" uuid "a20f8b2d-8c57-4965-bb1c-d142c5b66431"` to set the site UUID.
-   Enter `drush config:import` or `drush cim` to import the site configurations.
-   Enter `yes` to confirm the configuration import.

Troubleshooting
===============

### 1 Too Many Variables (SQLite variable limit issue when running drush cim)

-   Visit this [issue](https://www.drupal.org/project/drupal/issues/2031261) and apply the following [Patch](https://www.drupal.org/files/issues/2020-03-30/2031261-112.patch).

### Site UUID in Source Storage Does Not Match the Target Storage (when running drush cim)

-   Visit this [issue](https://www.drupal.org/project/drupal/issues/3047392) and apply the following [Patch](https://www.drupal.org/files/issues/2020-03-28/3047392-17_0.patch).
-   Typically occurs when the uuid of the current site does not match the uuid in the configuration you are attempting to import (see system.site.yml file).
-   Enter `drush cget system.site` to see the current site information.
-   Enter `drush config-set "system.site" uuid "a20f8b2d-8c57-4965-bb1c-d142c5b66431"` to set the site UUID.

### Unexpected Error During Import With Operation Delete

-   Attempt to create a field body that does not exist on entity type node.
-   Attempt to creat a field field_xyz that does not exist on entity type z.
-   This issue typically occurs when a yml file is deleted and re-created within the same import (avoid doing this).
-   To resolve the issue of config files being deleted and re-created within the same import, export your configuration files to a new folder right after the fresh Drupal install. Next, copy the files that were mentioned in the error from the new folder and paste them in the sync folder from the repo (folder you want to import). When prompoted, select to overwrite the existing files in the sync folder with the copied files from the new folder. Confirm that the next time you run drush cim, that the files that were previously throwing errors are not listed in the import statment (since they are exactly the same between the new folder and the sync).

### General Error: 1 Near "XYZ": Syntax Error

-   This error is indicative of a syntax error within one or more of the SQL statments made during the config import process. The erroneous query is typically shown next to the error.
-   To resolve this issue, copy the erroneous query, open your DBMS and manually enter/execute the query. If the error continues, edit the SQL statement to remove any unsupported functions or characters.

### Temporary File "XYZ" Could Not Be Created

-   This error occurs when the Temp folder is not writeable. To resolve this issue, update the write permissions for the Temp directory, or move it to a writeable location.
-   Open the `settings.php` file in the `IntraCMS\docroot\sites\default` directory using a text editor and make the following change:
    -   Set `$settings['file_temp_path'] = './files/temp';`.
-   Create a folder called `temp` in the `IntraCMS\docroot\sites\default\files` directory.
