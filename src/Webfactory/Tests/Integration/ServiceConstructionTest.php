<?php

namespace Webfactory\Tests\Integration;

use Symfony\Component\DependencyInjection\Container;

/**
 * Checks if it is possible to instantiate the configured services.
 */
class ServiceConstructionTest extends AbstractContainerTestCase
{
    /**
     * Checks if it is possible to create instances of the defined services.
     */
    public function testServicesCanBeInstantiated()
    {
        $container = $this->getContainer();

        $this->setExpectedException(null);
        foreach ($container->getServiceIds() as $id) {
            /* @var $id string */
            $container->get($id, Container::NULL_ON_INVALID_REFERENCE);
        }
    }
}
