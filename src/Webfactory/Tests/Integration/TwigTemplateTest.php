<?php

namespace Webfactory\Tests\Integration;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Webfactory\Util\ApplicationBundleIterator;
use Webfactory\Util\DataProviderArgumentIterator;
use Webfactory\Util\DataProviderIterator;
use Webfactory\Util\VendorResources;

/**
 * Checks the Twig templates in the project.
 */
class TwigTemplateTest extends AbstractContainerTestCase
{
    /**
     * Checks if the provided Twig templates can be compiled.
     *
     * @param string|null $templatePath The path to the template file.
     * @dataProvider templateFileProvider
     */
    public function testTemplateCanBeCompiled($templatePath = null)
    {
        if ($templatePath === null) {
            $this->markTestSkipped('No Twig templates found. Nothing to test.');
        }
        $loader = new \Twig_Loader_Filesystem(dirname($templatePath));
        $twig   = $this->getTwigEnvironment();
        // Add the new loader to be able to load the template directly.
        // The original loader must be preserved as Twig is otherwise
        // not able to resolve the inline references.
        $combinedLoader = new \Twig_Loader_Chain(array($loader, $twig->getLoader()));
        $twig->setLoader($combinedLoader);
        $fileName = basename($templatePath);

        $this->setExpectedException(null);
        $twig->loadTemplate($fileName);
    }

    /**
     * Provider that can be used by tests to retrieve the template file paths.
     *
     * @return array(array(string))
     */
    public function templateFileProvider()
    {
        $templateFiles = $this->getTemplateFiles();
        $templateData  = new DataProviderArgumentIterator($templateFiles);
        return new DataProviderIterator($templateData);
    }

    /**
     * Returns the Twig environment that is used by the application.
     *
     * The application specific environment must be used, as the default
     * one does not know about Symfony or custom extensions.
     *
     * @return \Twig_Environment
     */
    protected function getTwigEnvironment()
    {
        $environment = $this->getContainer()->get('twig', Container::NULL_ON_INVALID_REFERENCE);
        if (!($environment instanceof \Twig_Environment)) {
            $this->markTestSkipped('Twig is not enabled for this application.');
        }
        // Return a copy to ensure that the original service configuration is preserved.
        return clone $environment;
    }

    /**
     * Returns the paths to the template files in this application.
     *
     * @return array(string)
     */
    protected function getTemplateFiles()
    {
        $kernel = static::createClient()->getKernel();
        $viewDirectories = array();
        $globalResourceDirectory = $kernel->getRootDir() . '/Resources';
        if (is_dir($globalResourceDirectory)) {
            $viewDirectories[] = $globalResourceDirectory;
        }
        foreach (new ApplicationBundleIterator($kernel) as $bundle) {
            /* @var $bundle BundleInterface */
            $viewDirectory = $bundle->getPath() . '/Resources/views';
            if (is_dir($viewDirectory)) {
                $viewDirectories[] = $viewDirectory;
            }
        }
        $templates = $this->createFinder()->in($viewDirectories)->files()->name('*.*.twig');
        $templates = iterator_to_array($templates, false);
        $templates = array_map(function (SplFileInfo $file) {
            return $file->getRealPath();
        }, $templates);
        return $templates;
    }

    /**
     * Creates a finder instance that automatically excludes files from vendor directories.
     *
     * The Finder's exclude() method accepts only relative paths, therefore we have to use
     * a custom filter.
     *
     * @return Finder
     */
    protected function createFinder()
    {
        $finder = Finder::create()->filter(function (SplFileInfo $file) {
            return !VendorResources::isVendorFile($file);
        });
        return $finder;
    }
}
