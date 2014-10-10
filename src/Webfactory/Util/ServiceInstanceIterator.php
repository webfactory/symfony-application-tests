<?php

namespace Webfactory\Util;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Accepts a set of service IDs and allows the iteration over the corresponding
 * instantiated services.
 *
 * Skips services are synthetic or which depend on synthetic services.
 */
class ServiceInstanceIterator
{
    /**
     * Creates an iterator that creates the services with the given IDs.
     *
     * @param ContainerInterface $container
     * @param \Traversable $serviceIds
     */
    public function __construct(ContainerInterface $container, \Traversable $serviceIds)
    {

    }
}
