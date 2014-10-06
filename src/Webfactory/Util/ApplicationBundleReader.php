<?php

namespace Webfactory\Util;

/**
 * Allows iteration over bundles that are defined in the current application.
 *
 * External bundles (which are included from the vendor directory) are not included.
 */
class ApplicationBundleReader implements \IteratorAggregate
{
    /**
     * Returns the iterator.
     *
     * @return \Traversable
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     */
    public function getIterator()
    {
        // TODO: Implement getIterator() method.
    }
}
