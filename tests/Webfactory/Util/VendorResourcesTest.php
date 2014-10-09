<?php

namespace Webfactory\Util;

/**
 * Tests the vendor directory helper methods.
 */
class VendorResourcesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Checks if getVendorDirectory() returns the path to a directory.
     */
    public function testGetVendorDirectoryReturnsPathToDirectory()
    {
        $path = VendorResources::getVendorDirectory();

        $this->assertTrue(is_dir($path), '"' . $path . '" is not a directory path.');
    }

    /**
     * Checks if getVendorDirectory() returns the correct path.
     */
    public function testGetVendorDirectoryReturnsCorrectPath()
    {
        $vendorDirectory = __DIR__ . '/../../../vendor';

        $this->assertEquals(realpath($vendorDirectory), realpath(VendorResources::getVendorDirectory()));
    }
}
