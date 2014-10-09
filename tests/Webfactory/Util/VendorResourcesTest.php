<?php

namespace Webfactory\Util;
use Composer\Autoload\ClassLoader;
use Webfactory\TestBundle\Form\ContactType;

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

    /**
     * Ensures that isVendorClass() returns false if the source file of the given class
     * is not located in the vendor directory.
     */
    public function testIsVendorClassReturnsFalseIfClassIsNotLocatedInVendorDirectory()
    {
        $this->assertFalse(VendorResources::isVendorClass('\Webfactory\Util\VendorResources'));
    }

    /**
     * Ensures that isVendorClass() returns false if the name of an internal PHP class
     * is passed.
     */
    public function testIsVendorClassReturnsFalseIfNameOfInternalClassIsPassed()
    {
        $this->assertFalse(VendorResources::isVendorClass('\stdClass'));
    }

    /**
     * Ensures that isVendorClass() returns true if the name of a class from the vendor
     * directory is passed.
     */
    public function testIsVendorClassReturnsTrueIfClassIsLocatedInVendorDirectory()
    {
        $this->assertTrue(VendorResources::isVendorClass('\Composer\Autoload\ClassLoader'));
    }

    /**
     * Ensures that isVendorClass() returns false if an object is passed whose class
     * is not located in the vendor directory.
     */
    public function testIsVendorClassReturnsFalseIfClassOfObjectIsNotLocatedInVendorDirectory()
    {
        $this->assertFalse(VendorResources::isVendorClass(new ContactType()));
    }

    /**
     * Ensures that isVendorClass() returns true if an object is passed whose class
     * is located in the vendor directory.
     */
    public function testIsVendorClassReturnsTrueIfClassOfObjectIsLocatedInVendorDirectory()
    {
        $this->assertTrue(VendorResources::isVendorClass(new ClassLoader()));
    }

    /**
     * Ensures that isVendorClass() throws an exception if neither an object nor a valid
     * class name is passed.
     */
    public function testIsVendorClassThrowsExceptionIfNoClassNameOrObjectIsPassed()
    {
        $this->setExpectedException('InvalidArgumentException');
        VendorResources::isVendorClass('NoValidClassName');
    }

    public function testIsVendorFileReturnsFalseIfFileIsNotLocatedInVendorDirectory()
    {

    }

    public function testIsVendorFileReturnsTrueIfFileIsLocatedInVendorDirectory()
    {

    }

    public function testIsVendorFileReturnsFalseIfFileThatIsReferencedByFileObjectIsNotLocatedInVendorDirectory()
    {

    }

    public function testIsVendorFileReturnsTrueIfFileThatIsReferencedByFileObjectIsLocatedInVendorDirectory()
    {

    }

    public function testIsVendorFileThrowsExceptionIfNoValidFileReferenceIsProvided()
    {

    }
}
