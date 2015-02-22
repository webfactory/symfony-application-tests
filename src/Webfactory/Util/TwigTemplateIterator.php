<?php

namespace Webfactory\Util;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Iterator that iterates over the file paths of all Twig templates that belong
 * to the application (application *and* vendor bundles).
 */
class TwigTemplateIterator implements \IteratorAggregate
{
    /**
     * The kernel whose templates are returned.
     *
     * @var KernelInterface
     */
    protected $kernel = null;

    /**
     * Reads templates from the application with the given kernel.
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Returns an iterator that provides Twig template paths.
     *
     * @return \Traversable
     */
    public function getIterator()
    {
        $this->kernel->boot();
        $viewDirectories = array();
        $globalResourceDirectory = $this->kernel->getRootDir() . '/Resources';
        $viewDirectories[] = $globalResourceDirectory;
        foreach ($this->kernel->getBundles() as $bundle) {
            /* @var $bundle BundleInterface */
            $viewDirectory = $bundle->getPath() . '/Resources/views';
            $viewDirectories[] = $viewDirectory;
        }
        $viewDirectories = array_filter($viewDirectories, function ($directory) {
            return is_dir($directory);
        });
        if (count($viewDirectories) === 0) {
            return new \ArrayIterator();
        }
        $templates = Finder::create()->in($viewDirectories)->files()->name('*.*.twig');
        $templates = iterator_to_array($templates, false);
        $templates = array_map(function (SplFileInfo $file) {
            return $file->getRealPath();
        }, $templates);
        return new \ArrayIterator($templates);
    }


}
