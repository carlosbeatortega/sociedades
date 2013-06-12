<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = require __DIR__.'/../vendor/autoload.php';

// intl
if (!function_exists('intl_get_error_code')) {
    require_once __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';

    $loader->add('', __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs');
}
set_include_path(__DIR__.'/../vendor/Zend/library'.PATH_SEPARATOR.get_include_path());
set_include_path(__DIR__.'/../vendor/GoogleApi/src'.PATH_SEPARATOR.get_include_path());

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
