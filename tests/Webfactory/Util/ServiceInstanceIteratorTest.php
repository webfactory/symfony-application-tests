<?php

namespace Webfactory\Util;

/**
 * Tests the service instance iterator.
 */
class ServiceInstanceIteratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Initializes the test environment.
     */
    protected function setUp()
    {
        parent::setUp();

    }

    /**
     * Cleans up the test environment.
     */
    protected function tearDown()
    {

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
