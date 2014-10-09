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
     * Checks if the given class is located in the vendor directory.
     *
     * For convenience, it is also possible to pass an object whose
     * class is then checked.
     *
     * @param string|object $classNameOrObject
     * @return boolean
     * @throws \InvalidArgumentException If no valid class name or object is passed.
     */
    public static function isVendorClass($classNameOrObject)
    {
        $className = (is_object($classNameOrObject)) ?  get_class($classNameOrObject) : $classNameOrObject;
        if (!class_exists($className)) {
            $message = '"' . $className . '" is not the name of a loadable class.';
            throw new \InvalidArgumentException($message);
        }
        $reflection = new \ReflectionClass($className);
        return strpos($reflection->getFileName(), static::getVendorDirectory()) === 0;
    }

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
