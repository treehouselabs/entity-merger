<?php

$loader = require __DIR__ . '/../vendor/autoload.php';

$loader->addPsr4('TreeHouse\\IntegrationBundle\\', __DIR__ . '/TreeHouse/IntegrationBundle');


\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
