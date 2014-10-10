<?php

namespace Webfactory\Util;

/**
 * Tests the application service iterator.
 */
class ApplicationServiceIteratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Checks if the iterator is traversable.
     */
    public function testIsTraversable()
    {

    }

    /**
     * Ensures that the iterator provides the ID of a service that is defined in an application
     * bundle, but does not follow the prefix convention.
     */
    public function testProvidesServiceThatIsDefinedInApplicationBundleButDoesNotFollowConvention()
    {

    }

    /**
     * Ensures that the iterator provides the ID of a service that is defined in an application
     * bundle and follows the prefix convention.
     */
    public function testProvidesServiceThatIsDefinedInApplicationBundleAndFollowsConvention()
    {

    }

    /**
     * Checks if the iterator provides the ID of a service that is *not* defined by the extension
     * of an application bundle, but that follows the prefix convention.
     *
     * This might be the case if a service is overwritten in the application config.
     */
    public function testProvidesServiceThatIsNotDefinedInExtensionButFollowsConvention()
    {

    }

    /**
     * Ensures that the iterator skips services that are defined in vendor bundles.
     */
    public function testSkipsServiceFromVendorBundle()
    {

    }
}
