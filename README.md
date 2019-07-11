# ORDatWork

The United States Environmental Protection Agency (EPA) GitHub project code is provided on an "as is" basis and the user assumes responsibility for its use.  EPA has relinquished control of the information and no longer has responsibility to protect the integrity , confidentiality, or availability of the information.  Any reference to specific commercial products, processes, or services by service mark, trademark, manufacturer, or otherwise, does not constitute or imply their endorsement, recommendation or favoring by EPA.  The EPA seal and logo shall not be used in any manner to imply endorsement of any commercial product or activity by EPA or the United States Government.

## Initial Setup

### Git initialization and dependency installation
1. From command line, run command  `git clone https://github.com/USEPA/ORDatWork.git`. If using [Acquia Dev Desktop (ADD)](https://dev.acquia.com/downloads), have the repository cloned inside `~Sites/devdesktop`
2. If required, enter git username/email and password when prompted.
3. `cd ORDatWork` -- Navigate into the cloned git repo 
4. `git checkout -b origin/development` -- checkout the development branch
5. `composer install` -- install all dependencies for the project. This can take some time.
    * For a Windows environment, this must be done in a BASH command line. Try GIT bash, included with the GIT installation, or Ubuntu Bash, new to Windows 10. 

### [Acquia Dev Desktop (ADD)](https://dev.acquia.com/downloads)
1. Open ADD
2. Click the **+** button from the bottom left corner
3. Select *Import local Drupal site...*
4. Set the *Local codebase folder* to the location you just cloned the repo to `~Sites\devdesktop\ORDatWork`
5. Set the *Local site name* to ORDatWork
6. Change the PHP version to 7.2.18 (or 7.3.x when ADD supports that)
7. Click **OK**
8. Once the site has been created in ADD, click on the local site URL to view the site in your browser.

#### Setup your config sync folder for ADD
1. Create a 'sync' folder at 'site/default/files/config_your_config_folder_name' path.
2: Update your settings.php file in the *Location of the site configuration files.* area with the following:

   ` $config_directories = [
      CONFIG_SYNC_DIRECTORY => 'sites/default/files/config_your_config_folder_name/sync',
    ];`

3. Check your sites Status Report page (/admin/reports/status#error) to ensure the error the sync error is not there.

