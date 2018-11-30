<?php

namespace Webfactory\Util;
use Composer\Autoload\ClassLoader;
use PHPUnit\Framework\TestCase;
use Webfactory\TestBundle\Form\ContactType;

/**
 * Tests the vendor directory helper methods.
 */
class VendorResourcesTest extends TestCase
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
        $this->expectException('InvalidArgumentException');
        VendorResources::isVendorClass('NoValidClassName');
    }

    /**
     * Ensures that isVendorFile() returns false if the given file is not located in
     * the vendor directory.
     */
    public function testIsVendorFileReturnsFalseIfFileIsNotLocatedInVendorDirectory()
    {
        $this->assertFalse(VendorResources::isVendorFile(__FILE__));
    }

    /**
     * Ensures that isVendorFile() returns true if the given file is located in the
     * vendor directory.
     */
    public function testIsVendorFileReturnsTrueIfFileIsLocatedInVendorDirectory()
    {
        $this->assertTrue(VendorResources::isVendorFile($this->getVendorFilePath()));
    }

    /**
     * Ensures that isVendorFile() returns false if a \SplFileObject is passed and the referenced
     * file is not located in the vendor directory.
     */
    public function testIsVendorFileReturnsFalseIfFileThatIsReferencedByFileObjectIsNotLocatedInVendorDirectory()
    {
        $this->assertFalse(VendorResources::isVendorFile(new \SplFileInfo(__FILE__)));
    }

    /**
     * Ensures that isVendorFile() returns true if a \SplFileObject is passed and the referenced
     * file is located in the vendor directory.
     */
    public function testIsVendorFileReturnsTrueIfFileThatIsReferencedByFileObjectIsLocatedInVendorDirectory()
    {
        $this->assertTrue(VendorResources::isVendorFile(new \SplFileInfo($this->getVendorFilePath())));
    }

    /**
     * Ensures that isVendorFile() returns false if the path to an application directory
     * is passed.
     */
    public function testIsVendorFileReturnsFalseIfApplicationDirectoryIsProvided()
    {
        $this->assertFalse(VendorResources::isVendorFile(__DIR__));
    }

    /**
     * Ensures that isVendorFile() returns true if the path to a directory
     * in the vendor folder is passed.
     */
    public function testIsVendorFileReturnsTrueIfVendorDirectoryIsProvided()
    {
        $directory = dirname($this->getVendorFilePath());
        $this->assertTrue(VendorResources::isVendorFile($directory));
    }

    /**
     * Ensures that isVendorFile() throws an exception if the given path does not reference
     * an existing file.
     */
    public function testIsVendorFileThrowsExceptionIfNoValidFileReferenceIsProvided()
    {
        $this->expectException('InvalidArgumentException');
        VendorResources::isVendorFile(__DIR__ . '/this/files/does/not/exist');
    }

    /**
     * Returns the path to a file in the vendor directory.
     *
     * @return string
     */
    protected function getVendorFilePath()
    {
        $reflection = new \ReflectionClass('Composer\Autoload\ClassLoader');
        return $reflection->getFileName();
    }
}
