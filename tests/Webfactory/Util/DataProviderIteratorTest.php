<?php

namespace Webfactory\Util;

use PHPUnit\Framework\TestCase;

/**
 * Tests the data provider iterator.
 */
class DataProviderIteratorTest extends TestCase
{
    /**
     * Checks if the iterator is traversable.
     */
    public function testIsTraversable()
    {
        $this->assertInstanceOf('Traversable', $this->createIterator(array()));
    }

    /**
     * Ensures that the iterator adds a single empty record if no data
     * was provided.
     */
    public function testAddsEntryIfDataSetIsEmpty()
    {
        $iterator = $this->createIterator(array());

        $data = iterator_to_array($iterator);
        $expectedData = array(
            array()
        );
        $this->assertEquals($expectedData, $data);
    }

    /**
     * Ensures that a non-empty data set is not modified.
     */
    public function testDoesNotChangeDataSetThatIsNotEmpty()
    {
        $originalDataSet = array(
            array(1, 2, 3),
            array(5, 4, 9)
        );
        $iterator = $this->createIterator($originalDataSet);

        $data = iterator_to_array($iterator);
        $this->assertEquals($originalDataSet, $data);
    }

    /**
     * Ensures that the iterator works with a traversable object as argument.
     */
    public function testIteratorWorksWithTraversable()
    {
        $originalDataSet = array(
            array(1, 2, 3),
            array(5, 4, 9)
        );
        $iterator = $this->createIterator(new \IteratorIterator(new \ArrayIterator($originalDataSet)));

        $data = iterator_to_array($iterator);
        $this->assertEquals($originalDataSet, $data);
    }

    /**
     * Creates an iterator that used the given data set.
     *
     * @param array(array(mixed))|\Traversable $dataSet
     * @return DataProviderIterator
     */
    protected function createIterator($dataSet)
    {
        return new DataProviderIterator($dataSet);
    }
}
