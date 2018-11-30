<?php

namespace Webfactory\Util;

use PHPUnit\Framework\TestCase;

/**
 * Tests the application service iterator.
 */
class ApplicationServiceIteratorTest extends TestCase
{
    /**
     * System under test.
     *
     * @var ApplicationServiceIterator
     */
    protected $iterator = null;

    /**
     * Initializes the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        $possibleServiceIds = array(
            'annotation_reader', // defined in vendor bundle
            'webfactory_test.form.contact_type', // defined in application bundle, follows convention
            'does_not_follow_service_id_convention',
            'webfactory_test.uses_prefix_but_not_defined_in_bundle'
        );
        $this->iterator = $this->createIterator($possibleServiceIds);
    }

    /**
     * Cleans up the test environment.
     */
    protected function tearDown()
    {
        $this->iterator = null;
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
     * Ensures that the iterator provides the ID of a service that is defined in an application
     * bundle, but does not follow the prefix convention.
     */
    public function testProvidesServiceThatIsDefinedInApplicationBundleButDoesNotFollowConvention()
    {
        $serviceIds = iterator_to_array($this->iterator);

        $this->assertContains('does_not_follow_service_id_convention', $serviceIds);
    }

    /**
     * Ensures that the iterator provides the ID of a service that is defined in an application
     * bundle and follows the prefix convention.
     */
    public function testProvidesServiceThatIsDefinedInApplicationBundleAndFollowsConvention()
    {
        $serviceIds = iterator_to_array($this->iterator);

        $this->assertContains('webfactory_test.form.contact_type', $serviceIds);
    }

    /**
     * Checks if the iterator provides the ID of a service that is *not* defined by the extension
     * of an application bundle, but that follows the prefix convention.
     *
     * This might be the case if a service is overwritten in the application config.
     */
    public function testProvidesServiceThatIsNotDefinedInExtensionButFollowsConvention()
    {
        $serviceIds = iterator_to_array($this->iterator);

        $this->assertContains('webfactory_test.uses_prefix_but_not_defined_in_bundle', $serviceIds);
    }

    /**
     * Ensures that the iterator skips services that are defined in vendor bundles.
     */
    public function testSkipsServiceFromVendorBundle()
    {
        $serviceIds = iterator_to_array($this->iterator);

        $this->assertNotContains('annotation_reader', $serviceIds);
    }

    /**
     * Checks if the iterator works even if objects are passed as service IDs (these
     * objects must provide a __toString() method that returns the service ID).
     */
    public function testIteratorWorksIfObjectsArePassedAsServiceIds()
    {
        $possibleServiceIds = array(
            $this->createServiceIdObject('webfactory_test.form.contact_type'),
            $this->createServiceIdObject('does_not_follow_service_id_convention')
        );
        $this->iterator = $this->createIterator($possibleServiceIds);

        $this->assertCount(2, $this->iterator);
    }

    /**
     * Ensures that the iterator returns the original objects if the service IDs are
     * provided as object
     */
    public function testIteratorReturnsServiceIdObjects()
    {
        $possibleServiceIds = array(
            $this->createServiceIdObject('webfactory_test.form.contact_type'),
            $this->createServiceIdObject('does_not_follow_service_id_convention')
        );
        $this->iterator = $this->createIterator($possibleServiceIds);

        $this->assertContainsOnly('stdClass', $this->iterator);
    }

    /**
     * Creates an object that returns the given service ID via __toString().
     *
     * @param string $serviceId
     * @return object
     */
    protected function createServiceIdObject($serviceId)
    {
        $object = $this->getMockBuilder('stdClass')->setMethods(['__toString'])->getMock();
        $object->expects($this->any())
               ->method('__toString')
               ->will($this->returnValue($serviceId));
        return $object;
    }

    /**
     * Creates an iterator that uses the given service IDs.
     *
     * @param array(string|object) $possibleServiceIds
     * @return ApplicationServiceIterator
     */
    protected function createIterator(array $possibleServiceIds)
    {
        return new ApplicationServiceIterator(new \TestKernel('test', true), new \ArrayIterator($possibleServiceIds));
    }
}
