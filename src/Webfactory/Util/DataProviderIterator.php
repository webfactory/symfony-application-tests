<?php

namespace Webfactory\Util;

/**
 * Helper class that simplifies the usage of data providers in tests.
 *
 * Automatically adds a null entries if the provided data set is empty.
 * The test that handles the provider data must be able to handle that case (0 arguments).
 */
class DataProviderIterator implements \IteratorAggregate
{
    /**
     * The data set.
     *
     * @var array(array(mixed))
     */
    protected $dataSet = null;

    /**
     * Creates an iterator that allows to traverse the given data set.
     *
     * @param array(array(mixed)) $dataSet
     */
    public function __construct(array $dataSet)
    {
        $this->dataSet = $dataSet;
    }

    /**
     * Returns the iterator.
     *
     * @return \Traversable
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     */
    public function getIterator()
    {
        if (count($this->dataSet) === 0) {
            return new \ArrayIterator(array(array()));
        }
        return new \ArrayIterator($this->dataSet);
    }
}
