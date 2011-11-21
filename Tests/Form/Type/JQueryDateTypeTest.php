<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Form\Type;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class JQueryDateTypeTest extends TypeTestCase
{
    public function testDefaultConfigs()
    {
        $form = $this->factory->create('genemu_jquerydate');
        $view = $form->createView();

        $date = new \DateTime();

        $this->assertEquals('en', $view->get('culture'));
        $this->assertEquals(array('showOn' => 'button'), $view->get('configs'));
        $this->assertEquals($date->format('Y') - 5, $view->get('min_year'));
        $this->assertEquals($date->format('Y') + 5, $view->get('max_year'));
        $this->assertEquals('yy-mm-dd', $view->get('javascript_format'));
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

        $this->assertEquals('en', $view->get('culture'));
        $this->assertEquals(array(
            'buttonImage' => '/images/date_button.png',
            'buttonImageOnly' => true,
            'showOn' => 'button'
        ), $view->get('configs'));
        $this->assertEquals($date->format('Y') - 5, $view->get('min_year'));
        $this->assertEquals($date->format('Y') + 5, $view->get('max_year'));
        $this->assertEquals('m/d/y', $view->get('javascript_format'));
    }

    public function testSingleTextFormatMediumConfigs()
    {
        $form = $this->factory->create('genemu_jquerydate', null, array(
            'widget' => 'single_text',
            'format' => \IntlDateFormatter::MEDIUM
        ));

        $view = $form->createView();

        $this->assertEquals(array(), $view->get('configs'));
        $this->assertEquals('M d, yy', $view->get('javascript_format'));
    }

    public function testSingleTextFormatLongConfigs()
    {
        $form = $this->factory->create('genemu_jquerydate', null, array(
            'widget' => 'single_text',
            'format' => \IntlDateFormatter::LONG
        ));

        $view = $form->createView();

        $this->assertEquals('MM d, yy', $view->get('javascript_format'));
    }

    public function testSingleTextFormatFullConfigs()
    {
        $form = $this->factory->create('genemu_jquerydate', null, array(
            'widget' => 'single_text',
            'format' => \IntlDateFormatter::FULL
        ));

        $view = $form->createView();

        $this->assertEquals('DD, MM d, yy', $view->get('javascript_format'));
    }

    public function testSingleTextRangeYearsConfigs()
    {
        $form = $this->factory->create('genemu_jquerydate', null, array(
            'years' => range(date('Y') - 10, date('Y') + 10),
            'widget' => 'single_text',
        ));

        $view = $form->createView();

        $date = new \DateTime();

        $this->assertEquals($date->format('Y') - 10, $view->get('min_year'));
        $this->assertEquals($date->format('Y') + 10, $view->get('max_year'));
    }
}
