<?php

/*
 * This file is part of the Genemu package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form;

use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Component\Form\FormViewInterface;

/**
 * Adds javascript and stylesheet sections support.
 *
 * @author Bilal Amarni <bilal.amarni@gmail.com>
 */
class GenemuRenderer extends TwigRenderer
{
    public function renderJavascript(FormViewInterface $view)
    {
        return $this->renderSection($view, 'javascript');
    }
    
    public function renderStylesheet(FormViewInterface $view)
    {
        return $this->renderSection($view, 'stylesheet');
    }
}
