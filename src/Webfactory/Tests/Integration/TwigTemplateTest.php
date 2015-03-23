<?php

namespace Webfactory\Tests\Integration;

use Symfony\Component\DependencyInjection\Container;
use Webfactory\Util\ApplicationFileIterator;
use Webfactory\Util\DataProviderArgumentIterator;
use Webfactory\Util\DataProviderIterator;
use Webfactory\Util\TwigTemplateIterator;

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
            // No Twig templates found. Nothing to test.
            return;
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
     * @return \Traversable
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
     * @return \Traversable
     */
    protected function getTemplateFiles()
    {
        $kernel = $this->getKernel();
        $templates = new TwigTemplateIterator($kernel);
        return new ApplicationFileIterator($templates);
    }
}
