<?php

namespace Webfactory\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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
