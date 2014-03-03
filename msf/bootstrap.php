<?php
/**
 * Register autoload function for msf classes
 */
spl_autoload_register(function($class) {
   if('msf\\' === substr($class, 0, 4)) {
        $_class = str_replace('msf\\', '', $class);
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
 * MSF package should be a subfolder of the site's webroot
 */
define('WEBROOT', dirname(__DIR__));

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
define('IMAGES_PATH', WEBROOT . DS . 'images');

