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

    public function testIsTraversable()
    {

    }

    public function testIteratorCreatesServiceWithGivenId()
    {

    }

    public function testIteratorDoesNotCreateServicesWhoseIdIsNotListed()
    {

    }

    public function testIteratorIgnoresSyntheticServicesEvenIfIdIsListed()
    {

    }

    public function testIteratorThrowsExceptionIfItIsNotPossibleToCreateService()
    {

    }
}
