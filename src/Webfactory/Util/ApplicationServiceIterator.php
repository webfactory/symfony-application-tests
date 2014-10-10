<?php

namespace Webfactory\Util;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
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
     * The kernel of the application whose services are accepted.
     *
     * @var KernelInterface
     */
    protected $kernel = null;

    /**
     * The services IDs that will be checked.
     *
     * @var \Traversable
     */
    protected $possibleServiceIds = null;

    /**
     * Creates the iterator.
     *
     * @param KernelInterface $applicationKernel
     * @param \Traversable $serviceIds The service IDs that are filtered.
     */
    public function __construct(KernelInterface $applicationKernel, \Traversable $serviceIds)
    {
        $this->kernel = $applicationKernel;
        $this->possibleServiceIds = $serviceIds;
    }

    /**
     * Returns the iterator.
     *
     * @return \Traversable
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     */
    public function getIterator()
    {
        $serviceIdWhitelist     = $this->getIdsOfServicesThatAreDefinedInApplication();
        $allowedServicePrefixes = $this->getPrefixesOfApplicationServices();
        $applicationServices    = array();
        foreach ($this->possibleServiceIds as $serviceId) {
            /* @var $serviceId string */
            if (in_array($serviceId, $serviceIdWhitelist)) {
                $applicationServices[] = $serviceId;
            } elseif ($this->startsWithPrefix($serviceId, $allowedServicePrefixes)) {
                $applicationServices[] = $serviceId;
            }
        }
        return new \ArrayIterator($applicationServices);
    }

    /**
     * Returns all IDs of services that are clearly defined in the application bundles.
     *
     * @return string[]
     */
    protected function getIdsOfServicesThatAreDefinedInApplication()
    {
        $builder = new ContainerBuilder();
        foreach (new ApplicationBundleIterator($this->kernel) as $bundle) {
            /* @var $bundle BundleInterface */
            $extension = $bundle->getContainerExtension();
            if ($extension !== null) {
                $extension->load(array(), $builder);
            }
        }
        return $builder->getServiceIds();
    }

    /**
     * Returns the prefixes that should be used by services that are defined in application bundles.
     *
     * @return string[]
     */
    protected function getPrefixesOfApplicationServices()
    {
        $prefixes = array();
        foreach (new ApplicationBundleIterator($this->kernel) as $bundle) {
            /* @var $bundle BundleInterface */
            $extension = $bundle->getContainerExtension();
            if ($extension !== null) {
                $prefixes[] = $extension->getAlias() . '.';
            }
        }
        return $prefixes;
    }

    /**
     * Checks if the given service ID starts with any of the provided prefixes.
     *
     * @param string $serviceId
     * @param string[] $allowedPrefixes
     * @return boolean
     */
    protected function startsWithPrefix($serviceId, $allowedPrefixes)
    {
        foreach ($allowedPrefixes as $prefix) {
            /* @var $prefix string */
            if (strpos($serviceId, $prefix) === 0) {
                return true;
            }
        }
        return false;
    }
}
