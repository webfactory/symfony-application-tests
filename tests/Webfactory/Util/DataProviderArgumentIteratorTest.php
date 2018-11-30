<?php

namespace Webfactory\Util;

use PHPUnit\Framework\TestCase;

/**
 * Tests the argument iterator.
 */
class DataProviderArgumentIteratorTest extends TestCase
{
    /**
     * Checks if the iterator is traversable.
     */
    public function testIsTraversable()
    {
        $this->assertInstanceOf('Traversable', $this->createIterator(array()));
    }

    /**
     * Ensures that the iterator does not change the number of entries of the
     * inner iterator.
     */
    public function testIteratorDoesNotChangeNumberOfEntries()
    {
        $iterator = $this->createIterator(array(1, 2, 3));

        $this->assertCount(3, $iterator);
    }

    /**
     * Checks if the iterator converts the entries from the inner iterator to
     * argument sets.
     */
    public function testIteratorConvertsOriginalEntriesToArgumentSets()
    {
        $iterator = $this->createIterator(array(1, 2, 3));

        $data = iterator_to_array($iterator);

        $expectedData = array(
            array(1),
            array(2),
            array(3)
        );
        $this->assertEquals($expectedData, $data);
    }

    /**
     * Checks if the iterator works with a traversable object.
     */
    public function testIteratorWorksWithTraversableObject()
    {
        $iterator = $this->createIterator(new \ArrayIterator(array(1)));

        $data = iterator_to_array($iterator);

        $expectedData = array(
            array(1)
        );
        $this->assertEquals($expectedData, $data);
    }

    /**
     * Creates an iterator that uses the given inner data.
     *
     * @param array(mixed)|\Traversable $innerData
     * @return DataProviderArgumentIterator
     */
    protected function createIterator($innerData)
    {
        return new DataProviderArgumentIterator($innerData);
    }
}
