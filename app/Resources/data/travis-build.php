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

    test_database_user: user

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

/**
 * Creates the phpunit XML config file
 *
 * @return void
 */
function createPhpUnitXml()
{
    $fileContent = <<<CONTENT
<?xml version="1.0" encoding="UTF-8"?>

<!-- http://www.phpunit.de/manual/current/en/appendixes.configuration.html -->
<phpunit
    backupGlobals               = "false"
    backupStaticAttributes      = "false"
    colors                      = "true"
    convertErrorsToExceptions   = "true"
    convertNoticesToExceptions  = "true"
    convertWarningsToExceptions = "true"
    processIsolation            = "false"
    stopOnFailure               = "false"
    syntaxCheck                 = "false"
    bootstrap                   = "bootstrap.php.cache" >

    <testsuites>
        <testsuite name="Tickit Test Suite">
            <directory>../src/*/*Bundle/Tests</directory>
            <directory>../src/*/Bundle/*Bundle/Tests</directory>
        </testsuite>
    </testsuites>

    <!--
    <php>
        <server name="KERNEL_DIR" value="/path/to/your/app/" />
    </php>
    -->

    <filter>
        <whitelist>
            <directory>../src</directory>
            <exclude>
                <directory>../src/*/*Bundle/Resources</directory>
                <directory>../src/*/*Bundle/Tests</directory>
                <directory>../src/*/Bundle/*Bundle/Resources</directory>
                <directory>../src/*/Bundle/*Bundle/Tests</directory>
            </exclude>
        </whitelist>
    </filter>

    <logging>
        <log type="coverage-clover" target="../build/logs/clover.xml"/>
    </logging>

</phpunit>
CONTENT;

    $appDir = getProjectRoot() . '/app';
    file_put_contents($appDir . '/phpunit.xml', $fileContent);

}

/**
 * Creates the cache directory for tickit cache bundle
 */
function createCacheDirectory()
{
    $root = getProjectRoot();
    mkdir($root . '/app/cache/test/tickit_cache', 0777, true);
}

function createBuildDirectory()
{
    $root = getProjectRoot();
    mkdir($root . '/build/logs', 0777, true);
}

createCacheDirectory();
createTravisConfigYml();
createPhpUnitXml();
createBuildDirectory();