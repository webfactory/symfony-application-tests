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
        $viewDirectories = $this->getPossibleViewDirectories();
        $viewDirectories = $this->removeNotExistingDirectories($viewDirectories);
        $templates       = $this->getTwigTemplatesIn($viewDirectories);
        return new \ArrayIterator($templates);
    }

    /**
     * Returns paths to directories that *might* contain Twig views.
     *
     * Please note, that it is not guaranteed that these directories exist.
     *
     * @return string[]
     */
    protected function getPossibleViewDirectories()
    {
        $viewDirectories = array();
        $globalResourceDirectory = $this->kernel->getRootDir() . '/Resources';
        $viewDirectories[] = $globalResourceDirectory;
        foreach ($this->kernel->getBundles() as $bundle) {
            /* @var $bundle BundleInterface */
            $viewDirectory = $bundle->getPath() . '/Resources/views';
            $viewDirectories[] = $viewDirectory;
        }
        return $viewDirectories;
    }

    /**
     * Removes all paths to not existing directories from the given list.
     *
     * @param string[] $directories
     * @return string[]
     */
    protected function removeNotExistingDirectories(array $directories)
    {
        $directories = array_filter($directories, function ($directory) {
            return is_dir($directory);
        });
        return $directories;
    }

    /**
     * Returns the paths to all Twig templates in the given directories.
     *
     * @param string[] $directories
     * @return string[]
     */
    protected function getTwigTemplatesIn($directories)
    {
        if (count($directories) === 0) {
            return array();
        }
        $templates = Finder::create()->in($directories)->files()->name('*.*.twig');
        $templates = iterator_to_array($templates, false);
        $templates = array_map(function (SplFileInfo $file) {
            return $file->getRealPath();
        }, $templates);
        return $templates;
    }
}
