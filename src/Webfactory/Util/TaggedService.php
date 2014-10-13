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
     * The ID of the service.
     *
     * @var string
     */
    protected $serviceId = null;

    /**
     * Definition of the tag.
     *
     * Contains all meta data that was added to the tag.
     *
     * @var array(string=>string)
     */
    protected $tagDefinition = null;

    /**
     * Creates an object with information about a tagged service.
     *
     * @param string $serviceId
     * @param array(string=>string) $tagDefinition
     */
    public function __construct($serviceId, array $tagDefinition)
    {
        $this->serviceId     = $serviceId;
        $this->tagDefinition = $tagDefinition;
    }

    /**
     * Returns the service ID.
     *
     * @return string
     */
    public function getServiceId()
    {
        return $this->serviceId;
    }

    /**
     * Returns the tag attributes.
     *
     * @return array(string=>string)
     */
    public function getTagDefinition()
    {
        return $this->tagDefinition;
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
        return $this->getServiceId();
    }
}
