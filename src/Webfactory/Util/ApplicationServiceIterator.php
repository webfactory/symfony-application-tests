<?php

namespace Webfactory\Util;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Allows iteration over services that are defined directly in the application.
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
 *
 * Optionally, the service IDs can be provided as objects that provide a __toString() method.
 */
class ApplicationServiceIterator extends \FilterIterator
{
    /**
     * The kernel of the application whose services are accepted.
     *
     * @var KernelInterface
     */
    protected $kernel = null;

    /**
     * IDs of services that are defined by the application.
     *
     * @var array(string)|null
     */
    protected $serviceIdWhitelist = null;

    /**
     * List of service prefixes that belong to the application.
     *
     * @var array(string)|null
     */
    protected $allowedServicePrefixes = null;

    /**
     * Creates the iterator.
     *
     * @param KernelInterface $applicationKernel
     * @param \Traversable $serviceIds The service IDs that are filtered.
     */
    public function __construct(KernelInterface $applicationKernel, \Traversable $serviceIds)
    {
        $this->kernel = $applicationKernel;
        parent::__construct(new \IteratorIterator($serviceIds));
    }

    /**
     * Checks if the current service ID is defined directly in the application.
     *
     * @return boolean True if the current element is acceptable, otherwise false.
     * @link http://php.net/manual/en/filteriterator.accept.php
     */
    public function accept()
    {
        /* @var $serviceId string|object */
        $serviceId = $this->current();
        if (in_array($serviceId, $this->getIdsOfServicesThatAreDefinedInApplication())) {
            return true;
        }
        if ($this->startsWithPrefix($serviceId, $this->getPrefixesOfApplicationServices())) {
            return true;
        }
        return false;
    }

    /**
     * Returns all IDs of services that are clearly defined in the application bundles.
     *
     * @return string[]
     */
    protected function getIdsOfServicesThatAreDefinedInApplication()
    {
        if ($this->serviceIdWhitelist === null) {
            $builder = new ContainerBuilder();
            $this->applyToExtensions(function (ExtensionInterface $extension) use ($builder) {
                $extension->load(array(), $builder);
            });
            $this->serviceIdWhitelist = $builder->getServiceIds();
        }
        return $this->serviceIdWhitelist;
    }

    /**
     * Returns the prefixes that should be used by services that are defined in application bundles.
     *
     * @return string[]
     */
    protected function getPrefixesOfApplicationServices()
    {
        if ($this->allowedServicePrefixes === null) {
            $this->allowedServicePrefixes = $this->applyToExtensions(function (ExtensionInterface $extension) {
                return $extension->getAlias() . '.';
            });
        }
        return $this->allowedServicePrefixes;
    }

    /**
     * Applies the given callback to all bundle extensions that are
     * defined in the application and returns an array with the results
     * of each call.
     *
     * @param callable $callback
     * @return array(mixed)
     */
    protected function applyToExtensions($callback)
    {
        $results = array();
        foreach (new ApplicationBundleIterator($this->kernel) as $bundle) {
            /* @var $bundle BundleInterface */
            $extension = $bundle->getContainerExtension();
            if ($extension !== null) {
                $results[] = call_user_func($callback, $extension);
            }
        }
        return $results;
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
