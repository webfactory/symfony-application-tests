<?php

namespace Webfactory\Util;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * Creates services from a container.
 *
 * Avoids errors in case of services that are synthetic or which depend on synthetic services.
 */
class ServiceCreator
{
    /**
     * The container that is used to retrieve service instances.
     *
     * @var ContainerInterface
     */
    protected $container = null;

    /**
     * Creates a factory for services from the given container.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
        try {
            return $this->container->get($serviceId, Container::NULL_ON_INVALID_REFERENCE);
        } catch (\Exception $e) {
            if ($this->isCausedBySyntheticServiceRequest($e)) {
                // Ignore errors that are caused by synthetic services. It is simply not
                // possible to create them in a reliable way.
                return null;
            }
            throw new CannotInstantiateServiceException($serviceId, 0, $e);
        }
    }

    /**
     * Checks if a request for a synthetic service caused the provided exception.
     *
     * @param \Exception $exception
     * @return boolean
     */
    protected function isCausedBySyntheticServiceRequest(\Exception $exception)
    {
        if (!($exception instanceof RuntimeException)) {
            return false;
        }
        return strpos($exception->getMessage(), 'requested a synthetic service') !== false;
    }
}
