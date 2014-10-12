<?php

namespace Webfactory\Util;

/**
 * Value object that holds information about a service and a single tag definition.
 *
 * A service can be tagged multiple times. This class contains only information about
 * a single tag definition. Therefore, a service with multiple tags canbe represented
 * by multiple value objects.
 */
class TaggedService
{
    /**
     * @param string $serviceId
     * @param array(string=>string) $tagDefinition
     */
    public function __construct($serviceId, array $tagDefinition)
    {

    }

    /**
     * @return string
     */
    public function getServiceId()
    {

    }

    /**
     * @return array(string=>string)
     */
    public function getTagDefinition()
    {

    }

    /**
     * @return string
     */
    public function __toString()
    {

    }
}
