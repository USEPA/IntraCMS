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

## Lightning quickstart:
You can start the site with no dependency on web servers or installed DB by using what Lightning is prepackaged with. Simply run the following command in the root of your project:
`composer quick-start`

This will install the PHP dependencies, install a SQLITE DB within the directory of your repo, and spin up a Travis server instance. While very simple, this can be a slow web server stack. Following guidelines below, it might be more beneficial to use your own preferred DB, Web Server, and PHP installation.


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
