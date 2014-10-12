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

    /**
     * Checks if the iterator is traversable.
     */
    public function testIsTraversable()
    {

    }

    /**
     * Ensures that the iterator cn handle the case when no service is
     * marked with the search tag.
     */
    public function testProvidesEmptyListIfNoServiceIsTagged()
    {

    }

    /**
     * Checks if the iterator provides a list of services with the search tag.
     */
    public function testProvidesListOfServicesWithTag()
    {

    }

    /**
     * Ensures that the iterator does not provide services that are marked with
     * another tag.
     */
    public function testDoesNotProvideServicesWithAnotherTag()
    {

    }
}
