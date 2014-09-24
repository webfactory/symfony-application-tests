<?php

namespace Webfactory\Tests\Integration;

/**
 * Checks the service container.
 */
class ContainerTest extends AbstractContainerTestCase
{
    /**
     * Checks if registered Twig extensions implement the correct interface.
     *
     * @param string $id
     * @param \Twig_ExtensionInterface|mixed $extension
     * @dataProvider getTwigExtensions
     */
    public function testRegisteredTwigExtensionsImplementCorrectInterface($id = null, $extension = null)
    {
        if ($id === null && $extension === null) {
            $this->markTestSkipped('No twig extensions registered, nothing to test.');
        }
        $message = 'Service "%s" is tagged as Twig extension, but it does not implement the required interface.';
        $message = sprintf($message, $id);
        $this->assertInstanceOf('\Twig_ExtensionInterface', $extension, $message);
    }

    /**
     * Returns the registered Twig extensions.
     *
     * @return array(array(string|object|null|array))
     */
    public function getTwigExtensions()
    {
        return $this->getTaggedServices('twig.extension', array('Symfony', 'Doctrine', 'Twig'));
    }
}
