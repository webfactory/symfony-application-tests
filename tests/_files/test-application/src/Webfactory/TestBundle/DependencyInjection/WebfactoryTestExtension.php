<?php

namespace Webfactory\TestBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

class WebfactoryTestExtension extends Extension
{
    /**
     * Loads service definitions.
     *
     * @param array $config
     * @param ContainerBuilder $container
     */
    public function load(array $config, ContainerBuilder $container)
    {
        // Process the config, which checks if the required parameter is set.
        $configuration = new Configuration();
        $this->processConfiguration($configuration, $config);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * Recommended alias for definitions.
     *
     * @return string
     */
    public function getAlias()
    {
        return 'webfactory_test';
    }
}
