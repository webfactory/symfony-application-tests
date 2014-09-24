<?php

namespace Webfactory\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * Base class for integration test cases.
 *
 * Provides access to kernel and container.
 */
abstract class AbstractContainerTestCase extends WebTestCase
{
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
        $container = static::createClient()->getContainer();
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
     * Each array contains the service ID as first, the service
     * as second and the tag definition as third item.
     *
     * @param string $tag
     * @param array(string) $namespacesToSkip A list of namespace prefixes that will be skipped.
     * @return array(array(object|null|string|array(string=>string)))
     */
    protected function getTaggedServices($tag, array $namespacesToSkip = array())
    {
        $container = $this->getContainerBuilder();
        $tagsById  = $container->findTaggedServiceIds($tag);
        $servicesAndDefinitions = array();
        foreach ($tagsById as $id => $tagDefinitions) {
            /* @var $id string */
            /* @var $tagDefinitions array(array(string=>string)) */
            if ($container->has($id)) {
                $class = $container->findDefinition($id)->getClass();
                foreach ($namespacesToSkip as $namespacePrefix) {
                    /* @var $namespacePrefix string */
                    if (strpos($class, $namespacePrefix) === 0) {
                        // Skip types that are defined in ignored namespaces.
                        continue 2;
                    }
                }
            }
            foreach ($tagDefinitions as $tagDefinition) {
                /* @var $tagDefinition array(string=>string) */
                $servicesAndDefinitions[] = array(
                    $id,
                    $container->get($id, Container::NULL_ON_INVALID_REFERENCE),
                    $tagDefinition
                );
            }
        }
        return $this->addFallbackEntryToProviderDataIfNecessary($servicesAndDefinitions);
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

    /**
     * If necessary, adds null entry to a list of method arguments, which were returned by a data provider.
     *
     * If a data provider returns an empty array, then the test fails. Therefore, this method adds an
     * entry if no data was gathered by a provider.
     * This entry does not provide any argument, so the test that works with the data should use
     * only optional parameters and handle that special case (for example by skipping the test).
     *
     * @param array(array(mixed)) $providerData
     * @return array(array(mixed))
     * @see https://phpunit.de/manual/3.9/en/writing-tests-for-phpunit.html#writing-tests-for-phpunit.data-providers
     */
    protected function addFallbackEntryToProviderDataIfNecessary(array $providerData)
    {
        if (count($providerData) === 0) {
            return array(array());
        }
        return $providerData;
    }
}
