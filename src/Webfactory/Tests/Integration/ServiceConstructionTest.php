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

        $message = '';
        foreach ($container->getServiceIds() as $id) {
            /* @var $id string */
            try {
                $container->get($id, Container::NULL_ON_INVALID_REFERENCE);
            } catch (\Exception $e) {
                $message .= 'Cannot create service "' . $id . '":' . PHP_EOL;
                $message .= $e . PHP_EOL . PHP_EOL;
            }
        }
        $this->assertTrue(strlen($message) === 0, $message);
    }
}
