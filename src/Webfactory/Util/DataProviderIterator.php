<?php

namespace Webfactory\Util;

/**
 * Helper class that simplifies the usage of data providers in tests.
 *
 * Automatically adds a null entries if the provided data set is empty.
 *
 * If a data provider returns an empty array, then the test fails. Therefore, this iterator adds an
 * entry if no data was gathered by a provider.
 * This entry does not provide any argument, so the test that works with the data should use
 * only optional parameters and handle that special case (for example by skipping the test).
 *
 * @see https://phpunit.de/manual/3.9/en/writing-tests-for-phpunit.html#writing-tests-for-phpunit.data-providers
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
