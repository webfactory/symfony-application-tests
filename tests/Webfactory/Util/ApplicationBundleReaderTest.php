<?php

namespace Webfactory\Util;

/**
 * Tests the reader that determines application bundles.
 */
class ApplicationBundleReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System under test.
     *
     * @var \Webfactory\Util\ApplicationBundleReader
     */
    protected $reader = null;

    /**
     * Initializes the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->reader = new ApplicationBundleReader(new \TestKernel('test', true));
    }

    /**
     * Cleans up the test environment.
     */
    protected function tearDown()
    {
        $this->reader = null;
        parent::tearDown();
    }

    /**
     * Checks if the reader is traversable.
     */
    public function testReaderIsTraversable()
    {

    }

    /**
     * Checks if the reader iterates over bundle instances.
     */
    public function testReaderIteratesOverBundles()
    {

    }

    /**
     * Ensures that the reader returns bundles that are defined in the application.
     */
    public function testReaderReturnsApplicationBundles()
    {

    }

    /**
     * Ensures that bundles, which are active in the application, but defined in the vendor
     * directory, are not returned by the reader.
     */
    public function testReaderDoesNotReturnActiveVendorBundles()
    {

    }
}
