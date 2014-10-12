<?php

namespace Webfactory\Util;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Tests the iterator for tagged services.
 */
class TaggedServiceIteratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System under test.
     *
     * @var TaggedServiceIterator
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
        $this->iterator  = new TaggedServiceIterator($this->container, 'test.tag');
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

    public function testProvidesEmptyListIfNoServiceIsTagged()
    {

    }

    public function testProvidesListOfServicesWithTag()
    {

    }

    public function testDoesNotProvideServicesWithAnotherTag()
    {

    }
}
