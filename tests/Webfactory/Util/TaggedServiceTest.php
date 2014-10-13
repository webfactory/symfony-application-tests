<?php

namespace Webfactory\Util;

/**
 * Tests the class that holds information about a tagged service.
 */
class TaggedServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System under test.
     *
     * @var TaggedService
     */
    protected $taggedService = null;

    /**
     * Initializes the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->taggedService = new TaggedService(
            'my.service',
            array(
                'alias' => 'test'
            )
        );
    }

    /**
     * Cleans up the test environment.
     */
    protected function tearDown()
    {
        $this->taggedService = null;
        parent::tearDown();
    }

    /**
     * Checks if getServiceId() returns the correct value.
     */
    public function testGetServiceIdReturnsCorrectValue()
    {
        $this->assertEquals('my.service', $this->taggedService->getServiceId());
    }

    /**
     * Checks if getTagDefinition() returns the correct value.
     */
    public function testGetTagDefinitionReturnsCorrectValue()
    {
        $expected = array(
            'alias' => 'test'
        );
        $this->assertEquals($expected, $this->taggedService->getTagDefinition());
    }

    /**
     * Checks if __toString() returns the service ID.
     */
    public function testToStringReturnsServiceId()
    {
        $this->assertEquals($this->taggedService->getServiceId(), (string)$this->taggedService);
    }
}
