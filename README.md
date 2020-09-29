# IntraCMS
The codebase of the US EPA's Intranet WebCMS

# Required Software & Setup
### PHP
* Download the latest version of PHP - https://windows.php.net/download.
* Add the PHP directory to the system environment PATH variable (typically done manually).
* Enter `php –v` in the command line (cmd) to confirm PHP is properly installed.
* Open the `php.ini` file in the PHP directory using a text editor and make the following changes:
   #### Resource Limits
  * Set `memory_limit = 256M`.
   #### Dynamic Extensions
  * Enable (remove leading semi-colon) `extension=gd2`.
  * Enable `extension=pdo_mysql`.
  * Enable `extension=pdo_pgsql`.
  * Enable `extension=pdo_sqlite`.
   #### Paths and Directories
  * Add `zend_extension = php_opcache.dll`.
   #### Module Settings
  * Enable (remove leading semi-colon) `opcache.enable=1`.
  * Enable `opcache.enable_cli=0`.
  * Enable `opcache.memory_consumption=128`.
  * Enable `opcache.interned_strings_buffer=8`.
  * Enable `opcache.max_accelerated_files=4000`.
  * Enable `opcache.revalidate_freq=60`.
  * Enable `opcache.file_cache_only=0`.
  * Add `opcache.fast_shutdown=1`.
### Composer
* Download and install the latest version of Composer - https://getcomposer.org/download.
* Add the Composer directory to the system environment PATH variable (typically occurs automatically during installation).
* Enter `composer –v` in the command line (cmd) to confirm Composer is properly installed.

### Git / Git Bash
* Download and install the latest version of Git - https://git-scm.com/downloads.
* Add the Git directory to the system environment PATH variable (typically occurs automatically during installation).
* Enter `git –-version` in the command line (cmd) to confirm Git is properly installed.

### Drush
*	Using Git Bash, enter `git clone https://github.com/drush-ops/drush.git` to clone Drush into your current directory.
*	Enter `cd drush` to navigate in to the cloned drush repo/folder.
* Enter `composer install` to install/update the project dependencies.
*	Enter `chmod u+x drush.bat` to make the file executable as a program by the owner.
*	Add the Drush directory to the system environment PATH variable (typically done manually).
* Enter `drush status` in the command line (cmd) to confirm Drush is properly installed.

### IIS Express
* Download and install the latest version of IIS Express - https://www.microsoft.com/en-us/download/details.aspx?id=48264.
* Add the IIS Express directory to the system environment PATH variable (typically done manually).
* Enter `iisexpress /?` in the command line (cmd) to confirm IIS Express is properly installed.

# Cloning Repository & Installing Dependencies
*	Using Git Bash, enter `git clone https://github.com/USEPA/IntraCMS.git` to clone the IntraCMS project into your current directory.
* If required, enter you Git username/email and password when prompted.
*	Enter `cd IntraCMS` to navigate in to the cloned IntraCMS repo/folder.
*	Enter `git checkout -b origin/development` to checkout the development branch.
* Enter `composer install` to install/update the project dependencies.
# Configuring & Starting Web-Server
* Open the `applicationhost.config` file in the `C:\Users\[USER]\Documents\IISExpress\config` directory using a text editor and make the following changes:
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
* To start the web server, run `iisexpress` in the command line. If you are managing multiple sites within the `applicationhost.config` file, run `iisexpress /site:[site_name]` or `iisexpress /site:[site_id]`
* Navigate to `http://localhost:8080/`. 
# Installing Drupal
* Select the `English` language when prompted.
* Select the `Lightning` installation profile when prompted.
* Select the `SQLite` database when prompted.
* Enter the site name, email address, username & password when prompted.
# Importing Drupal Configurations
### Update Sync Folder Location
* Open the `settings.php` file in the `IntraCMS\docroot\sites\default` directory using a text editor and make the following change:
  * Set `$settings['config_sync_directory'] = '../config/sync';`.
### Set Site UUID & Import Configurations
*	Using Git Bash, navigate to the IntraCMS cloned repo/folder.
* Enter `drush config-set "system.site" uuid "a20f8b2d-8c57-4965-bb1c-d142c5b66431"` to set the site UUID.
* Enter `drush config:import` or `drush cim`  to import the site configurations.
* Enter `yes` to confirm the configuration import.


