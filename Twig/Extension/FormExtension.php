<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Twig\Extension;

use Symfony\Bridge\Twig\Extension\FormExtension as BaseFormExtension;
use Symfony\Component\Form\FormView;

/**
 * FormExtension extends Twig with form capabilities.
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class FormExtension extends BaseFormExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'form_javascript' => new \Twig_Function_Method($this, 'renderJavascript', array('is_safe' => array('html'))),
            'form_stylesheet' => new \Twig_Function_Node('Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode', array('is_safe' => array('html'))),
        );
    }

    /**
     * Render Function Form Javascript
     *
     * @param FromView $view
     *
     * @return string
     */
    public function renderJavascript(FormView $view, $prototype = false)
    {
        $block = $prototype ? 'javascript_prototype' : 'javascript';

        return $this->renderer->searchAndRenderBlock($view, $block);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu.twig.extension.form';
    }
}
