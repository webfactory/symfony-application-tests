<?php

namespace Webfactory\Util;

/**
 * Value object that holds information about a service and a single tag definition.
 *
 * A service can be tagged multiple times. This class contains only information about
 * a single tag definition. Therefore, a service with multiple tags can be represented
 * by multiple value objects.
 */
class TaggedService
{
    /**
     * Creates an object with information about a tagged service.
     *
     * @param string $serviceId
     * @param array(string=>string) $tagDefinition
     */
    public function __construct($serviceId, array $tagDefinition)
    {

    }

    /**
     * Returns the service ID.
     *
     * @return string
     */
    public function getServiceId()
    {

    }

    /**
     * Returns the tag attributes.
     *
     * @return array(string=>string)
     */
    public function getTagDefinition()
    {

    }

    /**
     * Returns the service ID.
     *
     * This allows the usage of this object in cases, where usually a service
     * ID as string is required.
     *
     * @return string
     */
    public function __toString()
    {

    }
}
