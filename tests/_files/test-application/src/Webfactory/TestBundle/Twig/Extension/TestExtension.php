<?php

namespace Webfactory\TestBundle\Twig\Extension;

/**
 * An example extension for Twig.
 */
class TestExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('stripTags', 'strip_tags'),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'test';
    }
}
