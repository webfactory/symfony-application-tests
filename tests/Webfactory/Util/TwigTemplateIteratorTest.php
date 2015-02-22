<?php

namespace Webfactory\Util;

use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Tests the Twig template iterator.
 */
class TwigTemplateIteratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System under test.
     *
     * @var TwigTemplateIterator
     */
    protected $iterator = null;

    /**
     * Initializes the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->iterator = new TwigTemplateIterator(
            $this->createKernel('app', array(
                $this->createBundle('AnyBundle'),
                $this->createBundle('NoViewDirectoryBundle')
            ))
        );
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
     * Ensures that the iterator returns file paths.
     */
    public function testIteratorReturnsFilePaths()
    {
        $files = $this->getItems($this->iterator);
        foreach ($files as $file) {
            /* @var $file mixed */
            $this->assertInternalType('string', $file);
            $this->assertFileExists($file);
        }
    }

    /**
     * Checks if templates on application level are recognized.
     */
    public function testIteratesOverApplicationLevelTemplates()
    {
        $files = $this->getItems($this->iterator);
        $fileNames = array_map('basename', $files);

        $this->assertContains('custom-template.html.twig', $fileNames);
    }

    /**
     * Checks if bundle templates are recognized.
     */
    public function testIteratesOverBundleTemplates()
    {
        $files = $this->getItems($this->iterator);
        $fileNames = array_map('basename', $files);

        $this->assertContains('any.html.twig', $fileNames);
    }

    /**
     * Ensures that paths to normal files are not returned.
     */
    public function testDoesNotIteratorOverNonTemplates()
    {
        $files = $this->getItems($this->iterator);
        $fileNames = array_map('basename', $files);

        $this->assertNotContains('explanation.md', $fileNames);
    }

    /**
     * Ensures that the iterator works if no template exists.
     */
    public function testWorksIfNoTemplateFilesAreAvailable()
    {
        $kernel = $this->createKernel('no-views-app');
        $iterator = new TwigTemplateIterator($kernel);

        $this->setExpectedException(null);
        $this->getItems($iterator);
    }

    /**
     * Creates a mocked kernel that contains the given bundles.
     *
     * @param string $name The name of the application.
     * @param \Symfony\Component\HttpKernel\Bundle\BundleInterface[] $bundles
     * @return KernelInterface
     */
    protected function createKernel($name, array $bundles = array())
    {
        $kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $kernel->expects($this->any())
            ->method('getRoot')
            ->willReturn($this->getTestDataDirectory() . '/' . $name);
        $kernel->expects($this->any())
            ->method('getBundles')
            ->willReturn($bundles);
        return $kernel;
    }

    /**
     * Creates a mocked bundle.
     *
     * @param string $name
     * @return \Symfony\Component\HttpKernel\Bundle\BundleInterface
     */
    protected function createBundle($name)
    {
        $bundle = $this->getMock('Symfony\Component\HttpKernel\Bundle\BundleInterface');
        $bundle->expects($this->any())
            ->method('getPath')
            ->willReturn($this->getTestDataDirectory() . '/src/' . $name);
        return $bundle;
    }

    /**
     * Returns the path to the test data directory.
     *
     * @return string
     */
    protected function getTestDataDirectory()
    {
        return __DIR__ . '/_files/TwigTemplateIterator';
    }

    /**
     * Returns the items from the given iterator.
     *
     * @param \Traversable|mixed
     * @return mixed[]
     */
    protected function getItems($iterator)
    {
        $this->assertInstanceOf('Traversable', $iterator);
        return iterator_to_array($iterator);}
}
