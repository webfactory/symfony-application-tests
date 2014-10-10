<?php

namespace Webfactory\Util;

use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Allows iteration over service that are defined directly in the application.
 *
 * Services that are defined by vendor bundles etc. are filtered and skipped.
 *
 * This iterator combines 2 strategies to determine if a service is defined
 * directly in the application:
 *
 * - Try to determine, which services are defined by the extensions of the application bundles and
 *   whitelist these.
 * - Follow conventions and allow services that use the prefixes of the application bundle extensions.
 *
 * The iterator operates on a given list of service IDs and provides only the IDs of the services
 * that are defined in the application.
 */
class ApplicationServiceIterator implements \IteratorAggregate
{
    /**
     * Creates the iterator.
     *
     * @param KernelInterface $applicationKernel
     * @param \Traversable $serviceIds The service IDs that are filtered.
     */
    public function __construct(KernelInterface $applicationKernel, \Traversable $serviceIds)
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

    }
}
