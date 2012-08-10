<?php
/**
 * This file is used to initialise configs for the Travis CI build process
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */


/**
 * Gets the project root directory
 *
 * @return string
 */
function getProjectRoot()
{
    return getcwd();
}

/**
 * Generates parameters.yml for Travis build
 *
 * @return closure
 */
function createTravisConfigYml()
{
    $fileContent = <<<CONTENT
parameters:
    database_driver:   pdo_mysql
    database_host:     localhost
    database_port:     ~
    database_name:     tickit
    database_user:     root
    database_password:

    mailer_transport:  smtp
    mailer_host:       localhost
    mailer_user:       ~
    mailer_password:   ~

    locale:            en
    secret:            SomeRandomValue
CONTENT;

    $configDir = getProjectRoot() . '/app/config';
    file_put_contents($configDir . '/parameters.yml', $fileContent);

}

createTravisConfigYml();