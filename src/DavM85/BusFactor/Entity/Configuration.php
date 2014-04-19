<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 31/01/14
 * Time: 11:23
 */

namespace DavM85\BusFactor\Entity;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('git-proxy');

        $rootNode
            ->children()
                ->scalarNode('access_token')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('organisation')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('mirror_url')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->variableNode('satis_options')
                    ->defaultValue(array())
                ->end()
                ->scalarNode('lock_file')
                    ->defaultValue(sys_get_temp_dir() . '/BusFactor_build.lock')
                ->end()
                ->scalarNode('rootPath')
                    ->defaultValue($this->getRootPath())
                ->end()
                ->arrayNode('thresholds')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('lower')->defaultValue(10)->end()
                        ->scalarNode('higher')->defaultValue(40)->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    private function getRootPath()
    {
        return dirname(realpath($_SERVER['argv'][0]));
    }
}
