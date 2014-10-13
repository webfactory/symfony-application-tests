<?php

namespace Webfactory\Util;

/**
 * Iterator that converts a single dimensional list into argument lists
 * for methods that use a data provider.
 *
 * A single argument entry is an array that contains the one element for
 * each argument that is passed.
 */
class DataProviderArgumentIterator extends \IteratorIterator
{
    /**
     * Creates the iterator.
     *
     * @param \Traversable|array(mixed) $innerData
     */
    public function __construct($innerData)
    {
        $innerData = (is_array($innerData)) ? new \ArrayIterator($innerData) : $innerData;
        parent::__construct($innerData);
    }

    /**
     * Converts the original entry into an argument set (with single argument).
     *
     * @return array(mixed)
     */
    public function current()
    {
        return array(parent::current());
    }
}
