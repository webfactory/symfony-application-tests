<?php

namespace Webfactory\Util;

use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Iterator that iterates over the file paths of all Twig templates that belong
 * to the application (application *and* vendor bundles).
 */
class TwigTemplateIterator
{
    /**
     * Reads templates from the application with the given kernel.
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {

    }
}
