<?php

namespace Webfactory\Util;

/**
 * Helper methods to check if resources are located in the vendor directory.
 *
 * The implementation uses the file path of Composer's class loader to
 * determine the vendor directory.
 * This avoids problems, when the name of the vendor directory is changed.
 *
 * To make things more complex, it is even possible to change the vendor directory
 * by passing environment variables during "composer install" or "composer update".
 * In that case, the name of the vendor directory does not even appear in the composer.json.
 */
class VendorResources
{
    /**
     * Returns the path to the vendor directory.
     *
     * @return string
     */
    public static function getVendorDirectory()
    {
        $reflection = new \ReflectionClass('\Composer\Autoload\ClassLoader');
        $classLoaderFilePath = $reflection->getFileName();
        return dirname(dirname($classLoaderFilePath));
    }
}
