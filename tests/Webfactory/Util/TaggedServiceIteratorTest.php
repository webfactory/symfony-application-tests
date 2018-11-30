<?php

namespace Webfactory\Util;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Tests the iterator for tagged services.
 */
class TaggedServiceIteratorTest extends TestCase
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
        $this->assertInstanceOf('Traversable', $this->iterator);
    }

    /**
     * Ensures that the iterator cn handle the case when no service is
     * marked with the search tag.
     */
    public function testProvidesEmptyListIfNoServiceIsTagged()
    {
        $this->assertCount(0, $this->iterator);
    }

    /**
     * Checks if the iterator provides TaggedService objects.
     */
    public function testProvidesTaggedServiceObjects()
    {
        $this->addService('my.service', 'test.tag');

        $this->assertContainsOnly('Webfactory\Util\TaggedService', $this->iterator);
    }

    /**
     * Checks if the iterator provides a list of services with the search tag.
     */
    public function testProvidesListOfServicesWithTag()
    {
        $this->addService('my.service', 'test.tag');
        $this->addService('another.service', 'test.tag');

        $this->assertCount(2, $this->iterator);
    }

    /**
     * Ensures that the iterator does not provide services that are marked with
     * another tag.
     */
    public function testDoesNotProvideServicesWithAnotherTag()
    {
        $this->addService('my.service', 'another.tag');

        $this->assertCount(0, $this->iterator);
    }

    /**
     * Adds a tagged service to the container.
     *
     * @param string $serviceId
     * @param string $tag
     */
    protected function addService($serviceId, $tag)
    {
        $definition = new Definition('stdClass');
        $definition->addTag($tag, array('alias' => 'test'));
        $this->container->setDefinition($serviceId, $definition);
    }
}
