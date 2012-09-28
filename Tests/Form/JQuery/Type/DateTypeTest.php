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

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class DateTypeTest extends TypeTestCase
{
    public function testDefaultConfigs()
    {
        $form = $this->factory->create('genemu_jquerydate');
        $view = $form->createView();

        $date = new \DateTime();

        $this->assertEquals('en', $view->vars['culture']);
        $this->assertEquals(array(
            'showOn' => 'button',
            'dateFormat' => 'yy-mm-dd'
        ), $view->vars['configs']);
        $this->assertEquals($date->format('Y') - 5, $view->vars['min_year']);
        $this->assertEquals($date->format('Y') + 5, $view->vars['max_year']);
    }

    public function testSingleTextFormatShortConfigs()
    {
        $form = $this->factory->create('genemu_jquerydate', null, array(
            'widget' => 'single_text',
            'format' => \IntlDateFormatter::SHORT,
            'configs' => array(
                'buttonImage' => '/images/date_button.png',
                'buttonImageOnly' => true
            )
        ));

        $view = $form->createView();

        $date = new \DateTime();

        $this->assertEquals('en', $view->vars['culture']);
        $this->assertEquals(array(
            'buttonImage' => '/images/date_button.png',
            'buttonImageOnly' => true,
            'showOn' => 'button',
            'dateFormat' => 'm/d/y'
        ), $view->vars['configs']);
        $this->assertEquals($date->format('Y') - 5, $view->vars['min_year']);
        $this->assertEquals($date->format('Y') + 5, $view->vars['max_year']);
    }

    public function testSingleTextFormatMediumConfigs()
    {
        $form = $this->factory->create('genemu_jquerydate', null, array(
            'widget' => 'single_text',
            'format' => \IntlDateFormatter::MEDIUM
        ));

        $view = $form->createView();

        $this->assertEquals(array('dateFormat' => 'M d, yy'), $view->vars['configs']);
    }

    public function testSingleTextFormatLongConfigs()
    {
        $form = $this->factory->create('genemu_jquerydate', null, array(
            'widget' => 'single_text',
            'format' => \IntlDateFormatter::LONG
        ));

        $view = $form->createView();

        $this->assertEquals(array('dateFormat' => 'MM d, yy'), $view->vars['configs']);
    }

    public function testSingleTextFormatFullConfigs()
    {
        $form = $this->factory->create('genemu_jquerydate', null, array(
            'widget' => 'single_text',
            'format' => \IntlDateFormatter::FULL
        ));

        $view = $form->createView();

        $this->assertEquals(array('dateFormat' => 'DD, MM d, yy'), $view->vars['configs']);
    }

    public function testSingleTextRangeYearsConfigs()
    {
        $form = $this->factory->create('genemu_jquerydate', null, array(
            'years' => range(date('Y') - 10, date('Y') + 10),
            'widget' => 'single_text',
        ));

        $view = $form->createView();

        $date = new \DateTime();

        $this->assertEquals($date->format('Y') - 10, $view->vars['min_year']);
        $this->assertEquals($date->format('Y') + 10, $view->vars['max_year']);
    }
}
