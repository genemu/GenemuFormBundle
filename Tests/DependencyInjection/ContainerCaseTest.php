<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olchauvel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Genemu\Bundle\FormBundle\DependencyInjection\GenemuFormExtension;

/**
 * ContainerTest
 *
 * @author Olivier Chauvel <olivier@gmail.com>
 */
abstract class ContainerTest extends TestCase
{
    protected function createContainer(array $data = array())
    {
        return new ContainerBuilder(new ParameterBag(array_merge(array(
            'kernel.bundles'          => array('GenemuFormBundle' => 'Genemu\Bundle\FormBundle\GenemuFormBundle'),
            'kernel.cache_dir'        => __DIR__,
            'kernel.compiled_classes' => array(),
            'kernel.debug'            => false,
            'kernel.environment'      => 'test',
            'kernel.name'             => 'kernel',
            'kernel.root_dir'         => __DIR__,
        ), $data)));
    }

    protected function createContainerFromFile($file, $data = array())
    {
        $container = $this->createContainer($data);
        $container->registerExtension(new GenemuFormExtension());
        $this->loadFromFile($container, $file);

        $container->getCompilerPassConfig()->setOptimizationPasses(array());
        $container->getCompilerPassConfig()->setRemovingPasses(array());
        $container->compile();

        return $container;
    }
    
    protected function loadFromFile(ContainerBuilder $container, $file, $type = 'yml')
    {
        switch($type) {
            case 'xml':
                $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/xml'));
                break;
            case 'php':
                $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/php'));
                break;
            case 'yml':
                $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/yml'));
        }
        
        $loader->load($file.'.'.$type);
    }
}