<?php

namespace Webfactory\Util;

use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Allows iteration over bundles that are defined in the current application.
 *
 * External bundles (which are included from the vendor directory) are not included.
 */
class ApplicationBundleIterator extends \FilterIterator
{
    /**
     * Creates a reader that retrieves the bundles from the given kernel.
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $kernel->boot();
        parent::__construct(new \ArrayIterator($kernel->getBundles()));
    }

    /**
     * Checks if the current bundle is an application bundle.
     *
     * @return boolean True if the current element is acceptable, otherwise false.
     * @link http://php.net/manual/en/filteriterator.accept.php
     */
    public function accept()
    {
        /* @var $bundle BundleInterface */
        $bundle = $this->current();
        return !VendorResources::isVendorClass($bundle);
    }
}
