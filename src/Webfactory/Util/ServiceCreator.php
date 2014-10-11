<?php

namespace Webfactory\Util;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Creates services from a container.
 *
 * Avoids errors in case of services that are synthetic or which depend on synthetic services.
 */
class ServiceCreator
{
    /**
     * Creates a factory for services from the given container.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {

    }

    /**
     * Creates the service with the given ID.
     *
     * @param string $serviceId
     * @return object|null The service object or null if it cannot be created (for comprehensible reasons).
     * @throws CannotInstantiateServiceException If it is not possible to create the service.
     */
    public function create($serviceId)
    {

    }
}
