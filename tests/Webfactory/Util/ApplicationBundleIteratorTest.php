<?php

namespace Webfactory\Util;

use PHPUnit\Framework\TestCase;

/**
 * Tests the iterator that determines application bundles.
 */
class ApplicationBundleIteratorTest extends TestCase
{
    /**
     * System under test.
     *
     * @var \Webfactory\Util\ApplicationBundleIterator
     */
    protected $iterator = null;

    /**
     * Initializes the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->iterator = new ApplicationBundleIterator(new \TestKernel('test', true));
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
     * Checks if the reader is traversable.
     */
    public function testReaderIsTraversable()
    {
        $this->assertInstanceOf('Traversable', $this->iterator);
    }

    /**
     * Checks if the reader iterates over bundle instances.
     */
    public function testReaderIteratesOverBundles()
    {
        $this->assertContainsOnly('\Symfony\Component\HttpKernel\Bundle\BundleInterface', $this->iterator);
    }

    /**
     * Ensures that the reader returns bundles that are defined in the application.
     */
    public function testReaderReturnsApplicationBundles()
    {
        $bundleClasses = $this->getBundleClassesFromReader();

        $this->assertContains('Webfactory\TestBundle\WebfactoryTestBundle', $bundleClasses);
    }

    /**
     * Ensures that bundles, which are active in the application, but defined in the vendor
     * directory, are not returned by the reader.
     */
    public function testReaderDoesNotReturnActiveVendorBundles()
    {
        $bundleClasses = $this->getBundleClassesFromReader();

        $this->assertNotContains('Symfony\Bundle\FrameworkBundle\FrameworkBundle', $bundleClasses);
    }

    /**
     * Returns the classes of the bundles that are returned by the reader.
     *
     * @return string[]
     */
    protected function getBundleClassesFromReader()
    {
        return array_map('get_class', iterator_to_array($this->iterator));
    }
}
