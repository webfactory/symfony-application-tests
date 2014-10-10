<?php

namespace Webfactory\Util;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Tests the service instance iterator.
 */
class ServiceInstanceIteratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System under test.
     *
     * @var ServiceInstanceIterator
     */
    protected $iterator = null;

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
        $serviceIds      = new \ArrayIterator(array(
            'my.service'
        ));
        $this->iterator  = new ServiceInstanceIterator($this->container, $serviceIds);
    }

    /**
     * Cleans up the test environment.
     */
    protected function tearDown()
    {
        $this->iterator  = null;
        $this->container = null;
        parent::tearDown();
    }

    /**
     * Ensures that the iterator is traversable.
     */
    public function testIsTraversable()
    {
        $this->assertInstanceOf('Traversable', $this->iterator);
    }

    /**
     * Checks if the service with the listed ID is created.
     */
    public function testIteratorCreatesServiceWithGivenId()
    {
        $definition = new Definition('stdClass');
        $this->container->setDefinition('my.service', $definition);

        $this->assertContainsOnly('stdClass', $this->iterator);
        $this->assertEquals(1, iterator_count($this->iterator));
    }

    /**
     * Ensures that services whose ID was not passed to the iterator are not created.
     */
    public function testIteratorDoesNotCreateServicesWhoseIdIsNotListed()
    {
        $definition = new Definition('stdClass');
        $this->container->setDefinition('my.service', $definition);
        $anotherDefinition = new Definition('stdClass');
        $this->container->setDefinition('another.service', $anotherDefinition);

        $this->assertEquals(1, iterator_count($this->iterator));
    }

    /**
     * Ensures that synthetic services are ignored during instantiation, even if
     * their ID was passed to the iterator.
     */
    public function testIteratorIgnoresSyntheticServicesEvenIfIdIsListed()
    {
        $definition = new Definition();
        $definition->setSynthetic(true);
        $this->container->setDefinition('my.service', $definition);

        $this->assertEquals(0, iterator_count($this->iterator));
    }

    /**
     * Ensures that an exception is thrown if service creation fails because of
     * an invalid service definition.
     */
    public function testIteratorThrowsExceptionIfItIsNotPossibleToCreateService()
    {
        $definition = new Definition();
        $definition->setFactoryClass('Webfactory\Missing');
        $definition->setFactoryMethod('create');
        $this->container->setDefinition('my.service', $definition);

        $this->setExpectedException('Webfactory\Util\CannotInstantiateServiceException');
        iterator_to_array($this->iterator);
    }
}
