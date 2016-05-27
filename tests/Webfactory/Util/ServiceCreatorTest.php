<?php

namespace Webfactory\Util;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Tests the service creator.
 */
class ServiceCreatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System under test.
     *
     * @var ServiceCreator
     */
    protected $creator = null;

    /**
     * The container that is used in the tests.
     *
     * @var ContainerBuilder
     */
    protected $container = null;

    /**
     * Initializes the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->container = new ContainerBuilder();
        $this->creator   = new ServiceCreator($this->container);
    }

    /**
     * Cleans up the test environment.
     */
    protected function tearDown()
    {
        $this->creator   = null;
        $this->container = null;
        parent::tearDown();
    }

    /**
     * Checks if it is possible to create a correctly configured service.
     */
    public function testCreateValidService()
    {
        $definition = new Definition('stdClass');
        $this->container->setDefinition('my.service', $definition);

        $this->assertInstanceOf('stdClass', $this->creator->create('my.service'));
    }

    /**
     * Ensures that the creator returns null if a synthetic service is requested.
     */
    public function testCreatorReturnsNullIfSyntheticServiceIsRequested()
    {
        $definition = new Definition();
        $definition->setSynthetic(true);
        $this->container->setDefinition('my.service', $definition);

        $this->assertNull($this->creator->create('my.service'));
    }

    /**
     * Ensures that an exception is thrown if service creation fails because of
     * an invalid service definition.
     */
    public function testCreatorThrowsExceptionIfItIsNotPossibleToCreateService()
    {
        $definition = new Definition();
        // Use the new way to define factories if available or fall back to the
        // mechanism from previous Symfony versions.
        if (method_exists($definition, 'setFactory')) {
            $definition->setFactory(array('Webfactory\Missing', 'create'));
        } else {
            $definition->setFactoryClass('Webfactory\Missing');
            $definition->setFactoryMethod('create');
        }
        $this->container->setDefinition('my.service', $definition);

        $this->setExpectedException('Webfactory\Util\CannotInstantiateServiceException');
        $this->creator->create('my.service');
    }
}
