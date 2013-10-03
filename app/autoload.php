<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../vendor/autoload.php';

$loader->add('Doctrine\\Tests', __DIR__.'/../vendor/doctrine/orm/tests');

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
