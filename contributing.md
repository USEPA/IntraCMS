## Welcome!

We’re so glad you’re thinking about contributing to an EPA open source project! If you’re unsure about anything, just ask — or submit your issue or pull request anyway. The worst that can happen is we’ll politely ask you to change something. We appreciate all friendly contributions.

We encourage you to read this project’s CONTRIBUTING policy (you are here), its
LICENSE, and its [README](https://github.com/USEPA/open-source-projects/blob/master/readme.md).

All contributions to this project will be released under the MIT dedication. By submitting a pull request or issue, you are agreeing to comply with this waiver of copyright interest.

If you have any questions or want to read more, check out the [EPA Open Source Project Repo](https://github.com/USEPA/open-source-projects) and [EPA's Interim Open Source Code Guidance](https://developer.epa.gov/guide/open-source-code/).



# Contributing to EPAatWork

## Initial Setup

### Git initialization and dependency installation
Use a BASH command line (e.g. GIT BASH) for a Windows environment.
1. From command line, run command  `git clone https://github.com/USEPA/IntraCMS.git`. 
2. If required, enter git username/email and password when prompted.
3. `cd IntraCMS` -- Navigate into the cloned git repo 
4. `git checkout --track origin/development` -- checkout the development branch from remote
5. `composer install` -- install all dependencies for the project. This can take some time.
    * For a Windows environment, this must be done in a BASH command line. Try GIT bash, included with the GIT installation, or Ubuntu Bash, new to Windows 10. 


### [DDEV](https://ddev.readthedocs.io/)
1. From command line, run command  `git clone https://github.com/USEPA/IntraCMS.git`
2. `cd IntraCMS` -- Navigate into the cloned git repo 
3. `ddev config --project-type=drupal9 --docroot=docroot --create-docroot` 
4. Update the config.yaml file located in the .ddev directory:
    1. Make sure the docroot is set correctly: `docroot: docroot`
    2. Update to the appropriate php version - currently 7.4: `php_version: "7.4"`
    3. Set the correct composer version - currently 1: `composer_version: "1"`
5. `ddev composer site-install` Until the project installation profile transitions to minimal from standard, there will always be errors related to the shortcut menu. The install script removes existing shortcuts, sets the site UUID, enables config_split, and runs a `drush-cim`. The post install script removes web.config and install.php.
6. Update the config.yaml file located in the .ddev directory:
7. `ddev launch`

## Feature Development

### Developing new feature
1. If contributing to the project, first ensure you have your development environment set up. We recommend DDEV, but any local environment should work.
2. When working on a new feature, you must create a new GIT branch from the remote development branch. No branches can be made from staging or master. To create a new branch from development, do the following:
    1. `git fetch` -- updates your local GIT with the latest details from the remote repository.
    2. `git checkout -b [name of your feature branch] origin/development` -- This creates a new branch with the latest from origin/development.
    3. The git checkout command above will also point your repo to the new branch.
 3. Before starting work on your new branch, you must install any updates from the development branch. This can be dependencies in composer.json, or new configurations that manage your Drupal project's structure.
    1. `composer install` -- Update your dependencies with latest changes
    2. It's possible the a dependency was removed from composer.json and your project must be updated. If this is the case, you will be prompted after running `composer install` to run `composer update --lock`. This will only remove the dependencies from your local project. Never run `composer update` or delete the composer.lock file. 
    3. `drush config:import` -- Updates your Drupal Database with the last configuration changes. It will prompt you to confirm the configurations being imported. Type `y` and enter.   


### Committing and pushing code
1. While developing, it is good practice to commit and push your code often. 
2. Run `git add .` from project root to stage all changes. If you have files that should not be added, isolate your addition commands to specific files or directories. For example:
    1. `git add README.md` from project root only stages the file, README.md.
    2. `git add config/` stages the entire directory config
3. Run `git commit -m "Short and descriptive commit message"`, with a commit message that describes the work completed.
4. Before pushing your code, it is good practice to pull the latest changes from development. This makes sure you are up to date and not accidentally overwriting another developer's code.
    1. Run `git pull origin development`
    2. This pulls the latest code from development branch and merges into your local branch. As long as there are no merge conflicts, you are ready to continue deploying your code.
4. Run `git push`. If this is the first time pushing a new branch, you will be prompted to run `git push --set-upstream origin [local branch name]`. This will create a new branch in the remote repo with your changes. Afterwards you only need to run `git push` to update your remote branch.

### Deploying a new feature
1. After finishing a feature locally, you must deploy to the repo and create a pull request. Before deploying, you must update any changed configurations.
2. Run `drush config:export` or `drush cex` to take your local Drupal configurations and store them in code.
3. Follow the [steps on committing and pushing code](#committing-and-pushing-code) to deploy the configuration changes to your remote branch. 
4. In Github, create a pull request from your branch to merge into development. 
    1. Select your branch in the branch dropdown.
    2. Click "New pull request"
    3. In the "base" dropdown, select "development"
    3. Add any additional information needed in text field.
    4. Click "Create pull request"
5. Once pull request is approved, it can be merged into development by clicking "Merge pull request".
   
