<?php

namespace Webfactory\Util;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Allows iteration over services with a specific tag.
 */
class TaggedServiceIterator
{
    /**
     * Creates an iterator that traverses the service IDs with the provided tag.
     *
     * @param ContainerBuilder $container
     * @param string $tag
     */
    public function __construct(ContainerBuilder $container, $tag)
    {

    }
}
