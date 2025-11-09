<?php

Phar::mapPhar('guzzle.phar');

require_once 'phar://guzzle.phar/vendor/symfony/class-loader/Symfony/Contracts/ClassLoader/UniversalClassLoader.php';

$classLoader = new Symfony\Contracts\ClassLoader\UniversalClassLoader();
$classLoader->registerNamespaces(array(
    'Guzzle' => 'phar://guzzle.phar/src',
    'Symfony\\Contracts\\EventDispatcher' => 'phar://guzzle.phar/vendor/symfony/event-dispatcher',
    'Doctrine' => 'phar://guzzle.phar/vendor/doctrine/common/lib',
    'Monolog' => 'phar://guzzle.phar/vendor/monolog/monolog/src'
));
$classLoader->register();

__HALT_COMPILER();
