<?php
/**
 * Sphinx Search Bundle
 *
 * @link        https://github.com/ripaclub/sphinxsearch
 * @copyright   Copyright (c) 2015 RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace SphinxSearch\SphinxSearchBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SphinxSearchExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config['backends'] as $name => $backend) {
            $definition = new Definition(
                'SphinxSearch\SphinxSearchBundle\SphinxQL\SphinxQLAdapterFactory',
                [$backend['driver'], $backend['hostname'], $backend['port'], $backend['charset']]
            );

            $factoryName = sprintf("sphinxsearch.sphinxql.factory.%s", $name);
            $container->setDefinition($factoryName, $definition);

            $definition = new Definition('Zend\Db\Adapter\Adapter');
            $definition->setFactory(array(new Reference($factoryName), 'createSphinxQLAdapter'));
            $container->setDefinition(sprintf("sphinxsearch.sphinxql.%s", $name), $definition);
        }

    }
}
