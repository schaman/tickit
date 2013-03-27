<a href='http://travis-ci.org/#!/tickit-project/tickit'><img src='https://secure.travis-ci.org/tickit-project/tickit.png' /></a>

# Quick Setup Guide for Developers #

Tickit will eventually come with a self-installer, but whilst development is still on-going you get started with the project by
running through these steps...

1. Copy `app/config/parameters.yml.dist` to `app/config/parameters.yml` and add in your own database/mailer configuration.

2. To correctly set the permissions on the `cache` and `logs` directories, run the following commands from your server (Debian based systems)

        sudo setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs
        sudo setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs

   The `www-data` user should be replaced with whatever user your apache / nginx service is running as


3. Install Composer

   As Symfony2 uses [Composer][1] to manage its dependencies, Tickit manages external libraries the same way.

   If you don't have Composer yet, just run the following command from your project directory

        curl -s http://getcomposer.org/installer | php

   You then need to install vendor libraries using...

        php composer.phar install

   And again using [bower][2] for the 3rd party JS libraries...

       bower install

   It should install all required vendor bundles.

4. Run the following commands from the project directory in your terminal...

        php app/console doctrine:database:create
        php app/console doctrine:schema:create

   This should create your database schema from the entities in the bundles located in the `src` folder. If there are any problems ensure that your database privileges and credentials are okay.

5. Import data fixtures by running the following command in your project directory...

        php app/console doctrine:fixtures:load

[1]:  http://getcomposer.org/
[2]:  http://twitter.github.com/bower/