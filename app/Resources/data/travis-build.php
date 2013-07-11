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
    $configDir = getProjectRoot() . '/app/config';
    file_put_contents($configDir . '/parameters.yml', file_get_contents($configDir . '/parameters.yml.dist'));

}

/**
 * Creates the phpunit XML config file
 *
 * @return void
 */
function createPhpUnitXml()
{
    $appDir = getProjectRoot() . '/app';
    file_put_contents($appDir . '/phpunit.xml', file_get_contents($appDir . '/phpunit.xml.dist'));
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
