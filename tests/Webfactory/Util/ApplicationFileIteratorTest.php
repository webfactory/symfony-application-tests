<?php

namespace Webfactory\Util;

use PHPUnit\Framework\TestCase;

/**
 * Tests the application file iterator.
 */
class ApplicationFileIteratorTest extends TestCase
{
    /**
     * System under test.
     *
     * @var ApplicationFileIterator
     */
    protected $iterator = null;

    /**
     * Initializes the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->iterator = new ApplicationFileIterator(array(
            __FILE__,
            __DIR__,
            VendorResources::getVendorDirectory(),
            VendorResources::getVendorDirectory() . '/autoload.php'
        ));
    }

    /**
     * Cleans up the test environment.
     */
    protected function tearDown()
    {
        $this->iterator = null;
        parent::tearDown();
    }

    /**
     * Checks if the object is an iterator.
     */
    public function testIsIterator()
    {
        $this->assertInstanceOf('Traversable', $this->iterator);
    }

    /**
     * Ensures that the iterator keeps application files.
     */
    public function testKeepsApplicationFile()
    {
        $this->assertContains(__FILE__, $this->getFiles());
    }

    /**
     * Ensures that the iterator keeps application directories.
     */
    public function testKeepsApplicationDirectory()
    {
        $this->assertContains(__DIR__, $this->getFiles());
    }

    /**
     * Ensures that the iterator removes vendor files.
     */
    public function testRemovesVendorFile()
    {
        $this->assertNotContains(VendorResources::getVendorDirectory() . '/autoload.php', $this->getFiles());
    }

    /**
     * Ensures that the iterator removes vendor directories.
     */
    public function testRemovesVendorDirectory()
    {
        $this->assertNotContains(VendorResources::getVendorDirectory(), $this->getFiles());
    }

    /**
     * Returns the files that are provided by the iterator.
     *
     * @return string[]
     */
    protected function getFiles()
    {
        $this->assertInstanceOf('Traversable', $this->iterator);
        return iterator_to_array($this->iterator);
    }
}
