<?php

namespace Webfactory\Util;

use Symfony\Component\DependencyInjection\ContainerBuilder;

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

    }

    /**
     * Checks if the service with the listed ID is created.
     */
    public function testIteratorCreatesServiceWithGivenId()
    {

    }

    /**
     * Ensures that services whose ID was not passed to the iterator are not created.
     */
    public function testIteratorDoesNotCreateServicesWhoseIdIsNotListed()
    {

    }

    /**
     * Ensures that synthetic services are ignored during instantiation, even if
     * their ID was passed to the iterator.
     */
    public function testIteratorIgnoresSyntheticServicesEvenIfIdIsListed()
    {

    }

    /**
     * Ensures that an exception is thrown if service creation fails because of
     * an invalid service definition.
     */
    public function testIteratorThrowsExceptionIfItIsNotPossibleToCreateService()
    {

    }
}
