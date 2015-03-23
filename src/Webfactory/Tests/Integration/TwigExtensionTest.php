<?php

namespace Webfactory\Tests\Integration;

use Webfactory\Util\ServiceCreator;
use Webfactory\Util\TaggedService;

/**
 * Tests the registered Twig extensions.
 */
class TwigExtensionTest extends AbstractContainerTestCase
{
    /**
     * Checks if registered Twig extensions implement the correct interface.
     *
     * @param TaggedService $service
     * @dataProvider getTwigExtensions
     */
    public function testRegisteredTwigExtensionsImplementCorrectInterface(TaggedService $service = null)
    {
        if ($service === null) {
            // No twig extensions registered, nothing to test.
            return;
        }

        $creator = new ServiceCreator($this->getContainer());
        /* @var $extension \Twig_ExtensionInterface */
        $extension = $creator->create($service->getServiceId());
        $message = 'Service "%s" is tagged as Twig extension, but it does not implement the required interface.';
        $message = sprintf($message, $service->getServiceId());
        $this->assertInstanceOf('\Twig_ExtensionInterface', $extension, $message);
    }

    /**
     * Returns the registered Twig extensions.
     *
     * @return \Traversable
     */
    public function getTwigExtensions()
    {
        return $this->getTaggedServices('twig.extension');
    }
}
