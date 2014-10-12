<?php

namespace Webfactory\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Webfactory\Util\ApplicationServiceIterator;
use Webfactory\Util\DataProviderArgumentIterator;
use Webfactory\Util\DataProviderIterator;
use Webfactory\Util\TaggedServiceIterator;

/**
 * Base class for integration test cases.
 *
 * Provides access to kernel and container.
 */
abstract class AbstractContainerTestCase extends WebTestCase
{
    /**
     * Test client or null if it was not created yet.
     *
     * Use getClient() to retrieve a client instance.
     *
     * @var \Symfony\Bundle\FrameworkBundle\Client|null
     */
    protected $client = null;

    /**
     * Cleans up the test environment.
     */
    protected function tearDown()
    {
        $this->client = null;
        parent::tearDown();
    }

    /**
     * Returns the annotation reader that is used by the application.
     *
     * @return \Doctrine\Common\Annotations\Reader
     */
    protected function getAnnotationReader()
    {
        return $this->getContainer()->get('annotation_reader');
    }

    /**
     * Returns the container.
     *
     * @return \Symfony\Component\DependencyInjection\Container
     */
    protected function getContainer()
    {
        $container = $this->getClient()->getContainer();
        $message   = 'We need a container instance to inspect the services; '
                   . 'a class that implements the ContainerInterface is not enough.';
        $this->assertInstanceOf('\Symfony\Component\DependencyInjection\Container', $container, $message);
        return $container;
    }

    /**
     * Reads the container debug dump and creates a container builder.
     *
     * The container builder must be used to get information about tagged services.
     *
     * @return ContainerBuilder
     */
    protected function getContainerBuilder()
    {
        $containerDebugDefinition = $this->getContainer()->getParameter('debug.container.dump');
        if (!is_file($containerDebugDefinition)) {
            $message = 'This test requires the container debug dump.';
            $this->markTestSkipped($message);
        }
        $container = new ContainerBuilder();
        $loader    = new XmlFileLoader($container, new FileLocator());
        $loader->load($containerDebugDefinition);
        return $container;
    }

    /**
     * Returns tagged (custom) services from the service container.
     *
     * The result contains one array for each tag definition.
     * Tests that use it as data provider retrieve a TaggedService object as argument.
     *
     * @param string $tag
     * @return \Traversable
     */
    protected function getTaggedServices($tag)
    {
        $container           = $this->getContainerBuilder();
        $taggedServices      = new TaggedServiceIterator($container, $tag);
        $applicationServices = new ApplicationServiceIterator($this->getKernel(), $taggedServices);
        $dataSets            = new DataProviderIterator(new DataProviderArgumentIterator($applicationServices));
        return $dataSets;
    }

    /**
     * Returns a test client.
     *
     * This client is created once per test and can be used to retrieve the
     * container or the kernel.
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function getClient()
    {
        if ($this->client === null) {
            $this->client = static::createClient();
        }
        return $this->client;
    }

    /**
     * Returns the kernel that is used in the tests.
     *
     * @return \Symfony\Component\HttpKernel\KernelInterface
     */
    protected function getKernel()
    {
        return $this->getClient()->getKernel();
    }

    /**
     * Returns the class name of the kernel that is used in the tests.
     *
     * Besides Symfony's default kernel detection, this method allows providing a
     * kernel class via KERNEL_CLASS environment variable.
     * The provided class must be loadable by the class loader.
     *
     * @return string
     * @throws \InvalidArgumentException If a kernel class was provided, but it is not available via class loader.
     * @see http://symfony.com/doc/current/book/testing.html#your-first-functional-test
     */
    protected static function getKernelClass()
    {
        if (isset($_SERVER['KERNEL_CLASS'])) {
            if (!class_exists($_SERVER['KERNEL_CLASS'], true)) {
                $message = 'The kernel class that is provided via KERNEL_CLASS environment variable must '
                         . 'be available by class loader.';
                throw new \InvalidArgumentException($message);
            }
            return $_SERVER['KERNEL_CLASS'];
        }
        return parent::getKernelClass();
    }
}
