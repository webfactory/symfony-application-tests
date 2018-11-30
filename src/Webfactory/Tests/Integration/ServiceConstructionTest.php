<?php

namespace Webfactory\Tests\Integration;

use Webfactory\Util\ApplicationServiceIterator;
use Webfactory\Util\CannotInstantiateServiceException;
use Webfactory\Util\ServiceCreator;

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
        $container->set('webfactory_test.advanced.synthetic_service', new \stdClass());
        $errors    = array();
        $creator   = new ServiceCreator($container);
        $services  = new ApplicationServiceIterator(
            $this->getKernel(),
            new \ArrayIterator($container->getServiceIds())
        );
        foreach ($services as $id) {
            /* @var $id string */
            try {
                $creator->create($id);
            } catch (CannotInstantiateServiceException $e) {
                $errors[] = $e;
            }
        }
        $this->assertCount(0, $errors, implode(PHP_EOL . PHP_EOL, $errors));
    }
}
