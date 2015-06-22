<?php
/**
 * Sphinx Search Bundle
 *
 * @link        https://github.com/ripaclub/sphinxsearch
 * @copyright   Copyright (c) 2015 RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace SphinxSearch\SphinxSearchBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sphinxsearch');

        $rootNode
            ->children()
                ->arrayNode('backends')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('driver')->defaultValue("pdo_mysql")->end()
                            ->scalarNode('hostname')->defaultValue("127.0.0.1")->end()
                            ->scalarNode('port')->defaultValue(9306)->end()
                            ->scalarNode('charset')->defaultValue("UTF8")->end()
                        ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
