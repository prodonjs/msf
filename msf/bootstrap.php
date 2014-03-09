<?php
/* Autoloader for composer packages */
require_once('vendor/autoload.php');

/**
 * Register autoload function for msf classes
 */
spl_autoload_register(function($class) {
   if('msf\\' === substr($class, 0, 4)) {
        $_class = str_replace(array('msf\\', '\\'), array('', DIRECTORY_SEPARATOR), $class);
        require_once("{$_class}.php");
   }
});

/**
 * Cross-platform path seperator
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * Root of MSF package
 */
define('PACKAGE_ROOT', __DIR__);

/**
 * Root for logs
 */
define('LOGS_ROOT', PACKAGE_ROOT . DS . 'logs');

/**
 * MSF package should be a subfolder of the site's webroot
 */
define('SITE_ROOT', dirname(__DIR__));

/**
 * Default location for file data storage
 */
define('FILE_DATA_SOURCE_PATH', PACKAGE_ROOT . DS . 'data');

/**
 * Default location for test file data storage
 */
define('TEST_DATA_PATH', PACKAGE_ROOT . DS . 'tests' . DS . 'files');

/**
 * Default location for image data storage
 */
define('IMAGES_PATH', FILE_DATA_SOURCE_PATH . DS . 'images');

/**
 * Twig template location
 */
define('TEMPLATES_PATH', SITE_ROOT);

/**
 * Provide IMAGE_SRC as a constant, fully-qualified path from the
 * site's root to the IMAGES_PATH datastore
 */
$siteRoot = empty($_SERVER['DOCUMENT_ROOT']) ? SITE_ROOT : realpath($_SERVER['DOCUMENT_ROOT']);
/* MS&F main site is running in a subdirectory and not as VirtualHost */
if(SITE_ROOT !== $siteRoot) {
    $imagesSource = substr(IMAGES_PATH, strlen($siteRoot));
}
else {
    $imagesSource = substr(IMAGES_PATH, strlen(SITE_ROOT));
}
define('IMAGES_SRC', str_replace(DS, '/', $imagesSource));