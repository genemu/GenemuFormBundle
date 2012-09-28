<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Form\JQuery\Type;

use Genemu\Bundle\FormBundle\Tests\Form\Type\TypeTestCase;
use Genemu\Bundle\FormBundle\Form\JQuery\Type\ChosenType;

/**
 * @author Bilal Amarni <bilal.amarni@gmail.com>
 */
class ChosenTypeTest extends TypeTestCase
{
    public function testDefaultConfig()
    {
        $form = $this->factory->create(new ChosenType('country'));

        $view = $form->createView();

        $this->assertEquals(true, $view->vars['allow_single_deselect']);
    }

    public function testConstructorAffectsParentType()
    {
        $form = $this->factory->create(new ChosenType('country'));

        $this->assertEquals(
            'country',
            $form->getConfig()->getType()->getParent()->getName()
        );
    }
}
