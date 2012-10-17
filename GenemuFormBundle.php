<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle;

use Genemu\Bundle\FormBundle\DependencyInjection\Compiler\FormPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * An extends of Symfony\Component\HttpKernel\Bundle\Bundle
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class GenemuFormBundle extends Bundle
{
    public static $types = array(
        'captcha', 'recaptcha', 'tinymce', 'date', 'file', 'image', 'autocomplete',
        'select2', 'chosen', 'autocompleter', 'tokeninput'
    );

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new FormPass());
    }
}
