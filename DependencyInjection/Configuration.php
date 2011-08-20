<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olchauvel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 *
 * @author Olivier Chauvel <olchauvel@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
     /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('genenu_form');

        $this->addTinymce($rootNode);
        $this->addReCaptcha($rootNode);
        $this->addJQueryDate($rootNode);

        return $treeBuilder;
    }

    protected function addTinymce(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('tinymce')
                    ->isRequired()
                    ->children()
                        ->variableNode('theme')->defaultValue('advanced')->end()
                        ->variableNode('script_url')->defaultNull()->end()
                        ->variableNode('configs')->defaultValue(array())->end()
                    ->end()
                ->end()
            ->end();
    }

    protected function addReCaptcha(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('recaptcha')
                    ->isRequired()
                    ->children()
                        ->variableNode('public_key')->defaultNull()->end()
                        ->variableNode('private_key')->defaultNull()->end()
                        ->arrayNode('options')
                            ->isRequired()
                            ->children()
                                ->variableNode('theme')->defaultValue('clean')->end()
                                ->booleanNode('use_ssl')->defaultFalse()->end()
                                ->variableNode('server_url')->defaultValue('http://api.recaptcha.net')->end()
                                ->variableNode('server_url_ssl')->defaultValue('https://api-secure.recaptcha.net')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    protected function addJQueryDate(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('jquerydate')
                    ->isRequired()
                    ->children()
                        ->variableNode('configs')->defaultValue(array())->end()
                    ->end()
                ->end()
            ->end();
    }
}
