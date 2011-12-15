<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Genemu\Bundle\FormBundle\DependencyInjection\Compiler\FormPass;

/**
 * An extends of Symfony\Component\HttpKernel\Bundle\Bundle
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class GenemuFormBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        /*
        $driver = new \Genemu\Component\Image\Driver\GDDriver();

        $image = new \Genemu\Component\Image\Image('/home/olivier/blur_before.png', true, $driver);

        var_dump($image->show('jpeg'));
        exit();
        */

        parent::build($container);
        $container->addCompilerPass(new FormPass());
    }
}
