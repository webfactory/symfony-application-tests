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
}
