<?php

namespace Webfactory\Util;

use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Allows iteration over bundles that are defined in the current application.
 *
 * External bundles (which are included from the vendor directory) are not included.
 */
class ApplicationBundleReader implements \IteratorAggregate
{
    /**
     * Creates a reader that retrieves the bundles from the given kernel.
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {

    }

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
