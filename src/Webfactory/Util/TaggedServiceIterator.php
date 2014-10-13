<?php

namespace Webfactory\Util;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Allows iteration over services with a specific tag.
 */
class TaggedServiceIterator implements \IteratorAggregate
{
    /**
     * The container that is used to retrieve the service information.
     *
     * @var ContainerBuilder
     */
    protected $container = null;

    /**
     * The search tag.
     *
     * @var string
     */
    protected $tag = null;

    /**
     * Creates an iterator that traverses the service IDs with the provided tag.
     *
     * @param ContainerBuilder $container
     * @param string $tag
     */
    public function __construct(ContainerBuilder $container, $tag)
    {
        $this->container = $container;
        $this->tag       = $tag;
    }

    /**
     * Returns the iterator.
     *
     * @return \Traversable
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     */
    public function getIterator()
    {
        $tagsById       = $this->container->findTaggedServiceIds($this->tag);
        $taggedServices = array();
        foreach ($tagsById as $id => $tagDefinitions) {
            /* @var $id string */
            /* @var $tagDefinitions array(array(string=>string)) */
            foreach ($tagDefinitions as $tagDefinition) {
                /* @var $tagDefinition array(string=>string) */
                $taggedServices[] = new TaggedService($id, $tagDefinition);
            }
        }
        return new \ArrayIterator($taggedServices);
    }
}
