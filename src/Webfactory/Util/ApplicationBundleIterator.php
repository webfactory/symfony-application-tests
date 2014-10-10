<?php

namespace Webfactory\Util;

use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Allows iteration over bundles that are defined in the current application.
 *
 * External bundles (which are included from the vendor directory) are not included.
 */
class ApplicationBundleIterator implements \IteratorAggregate
{
    /**
     * The kernel whose bundles are filtered.
     *
     * @var KernelInterface
     */
    protected $kernel = null;

    /**
     * Creates a reader that retrieves the bundles from the given kernel.
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Returns the iterator.
     *
     * @return \Traversable
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     */
    public function getIterator()
    {
        $this->kernel->boot();
        $bundles = $this->kernel->getBundles();
        $applicationBundles = array_filter($bundles, function (BundleInterface $bundle) {
            return !VendorResources::isVendorClass($bundle);
        });
        return new \ArrayIterator($applicationBundles);
    }
}
