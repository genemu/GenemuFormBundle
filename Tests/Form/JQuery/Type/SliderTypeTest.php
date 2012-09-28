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

use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

use Genemu\Bundle\FormBundle\Tests\Form\Type\TypeTestCase;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class SliderTypeTest extends TypeTestCase
{
    public function testDefaultConfigs()
    {
        $form = $this->factory->create('genemu_jqueryslider');
        $view = $form->createView();

        $configs = $view->vars['configs'];

        $this->assertEquals(0, $configs['min']);
        $this->assertEquals(100, $configs['max']);
        $this->assertEquals(1, $configs['step']);
        $this->assertEquals('horizontal', $configs['orientation']);
    }

    public function testConfigs()
    {
        $form = $this->factory->create('genemu_jqueryslider', null, array(
            'min' => 10,
            'max' => 1000,
            'step' => 5,
            'orientation' => 'vertical'
        ));

        $view = $form->createView();

        $configs = $view->vars['configs'];

        $this->assertEquals(10, $configs['min']);
        $this->assertEquals(1000, $configs['max']);
        $this->assertEquals(5, $configs['step']);
        $this->assertEquals('vertical', $configs['orientation']);
    }

    public function testFaultOrientation()
    {
        try {
            $form = $this->factory->create('genemu_jqueryslider', null, array('orientation' => 'verical'));
        } catch (InvalidOptionsException $expected) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }
}
