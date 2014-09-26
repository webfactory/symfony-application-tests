<?php

namespace Webfactory\Tests\Integration;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

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
                if (($e instanceof RuntimeException) && strpos($e->getMessage(), 'requested a synthetic service') !== false) {
                    // Skip services that are or that depend on synthetic services. It is simply not
                    // possible to create them in a reliable way.
                    continue;
                }
                $message .= 'Cannot create service "' . $id . '":' . PHP_EOL;
                $message .= $e . PHP_EOL . PHP_EOL;
            }
        }
        $this->assertTrue(strlen($message) === 0, $message);
    }
}
