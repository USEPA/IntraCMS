# ORDatWork

The United States Environmental Protection Agency (EPA) GitHub project code is provided on an "as is" basis and the user assumes responsibility for its use.  EPA has relinquished control of the information and no longer has responsibility to protect the integrity , confidentiality, or availability of the information.  Any reference to specific commercial products, processes, or services by service mark, trademark, manufacturer, or otherwise, does not constitute or imply their endorsement, recommendation or favoring by EPA.  The EPA seal and logo shall not be used in any manner to imply endorsement of any commercial product or activity by EPA or the United States Government.

This is a Composer-based installer for the [Lightning](https://www.drupal.org/project/lightning) Drupal distribution. Welcome to the future!

## System Requirements

* PHP 7.3.7
    * Install PHP 7.3.7 from https://www.php.net/
    * Update PATH variables to include PHP bin directory. Test with `php -v` to confirm availability in command line.
* Composer
    * Install latest stable version of Composer. Composer is a dependency management utiltiy for PHP packages and projects. Visit https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos to view installation options.
    * Update PATH variables to include composer.phar parent directory. Test with `composer -v` to confirm availability in command line.
* Database
    * Use database of preference. The no-hassle method is to use SQLite, a file based DB supported by Lightning. 
* Git    
    * Install from https://git-scm.com/downloads. 
    * After running installer, test in command line via `git -v`.
* Web server.
    * A web server, such as Apache, is required to serve a PHP site. One possibility is to use a MAMP/WAMP installation.
        * http://www.wampserver.com/en/
        * https://www.mamp.info/en/
    * WAMP (Windows, Apache, MySQL, PHP) or MAMP (Mac, Apache, MySQL, PHP) installations can deliver the full stack required with a simple installation.
    * IISExpress uses Windows web server. (See IISExpress section below)
    
## Git initialization and dependency installation
1. From command line, run command  `git clone https://github.com/USEPA/ORDatWork.git`.
2. If required, enter git username/email and password when prompted.
3. `cd ORDatWork` -- Navigate into the cloned git repo
4. `git checkout origin/development` -- checkout the development branch
5. `composer install` -- install all dependencies for the project. This can take some time.
    * For a Windows environment, this must be done in a BASH command line. Try GIT bash, included with the GIT installation, or Ubuntu Bash, new to Windows 10. 

## Database setup
1. Navigate to site in browser. (URL depends on your webserver. It can be localhost:8080 for example.)
2. Drupal will prompt you to select site settings. First is the language, then the database.
3. For the database, select SQLite for a simple installation. The DB location has defaulted to a file store in the drupal directory. Keep this as default.
4. Once submitting the DB settings, the installation of the DB will take some time.
5. Once completed, enter your preferred admin name and credentials.

## IIS Express
* Install for Windows machines here: https://www.microsoft.com/en-us/download/details.aspx?id=48264
* Once installed, `iisexpress` is available via command line. Test with `iisexpress /?`
* The configuration file for your IIS sites lives in C:\Users\[current_user]\Documents\IISExpress\config
* To add a site configuration, edit applicationhost.config:
   ```xml
    <site name="ord" id="1">
        <application path="/" applicationPool="Clr4IntegratedAppPool">
            <virtualDirectory path="/" physicalPath="[Full path to ORD@WORK site root]\docroot" />
        </application>
        <bindings>
            <binding protocol="http" bindingInformation="*:8080:localhost" />
        </bindings>
    </site>
    ```
    *  The bindingInformation section can have a different port (other than 8080) if required. Keep Application Pool setting to the default.
* Add the following within the system.webServer tag
    ```xml
   <fastCgi>
        <application fullPath="[Full path to PHP installation directory]\php-cgi.exe" stderrMode="ReturnStdErrIn500" activityTimeout="370">
            <environmentVariables>
                <environmentVariable name="PHPRC" value="[Full path to PHP installation directory]" />
            </environmentVariables>
        </application>
    </fastCgi>
    ```
* To start the web server, run `iisexpress` in the command line. If you are managing multiple sites within the config, run `iisexpress /site:[site_name]` or `iisexpress /site:[site_id]`