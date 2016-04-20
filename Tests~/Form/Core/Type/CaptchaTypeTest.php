<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Form\Core\Type;

use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Genemu\Bundle\FormBundle\Tests\Form\Type\TypeTestCase;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class CaptchaTypeTest extends TypeTestCase
{
    public function setUp()
    {
        parent::setUp();

        if (!function_exists('gd_info')) {
            $this->markTestSkipped('Gd not installed');
        }
    }

    public function testDefaultConfigs()
    {
        $form = $this->factory->create('genemu_captcha');
        $view = $form->createView();
        $captcha = $form->getConfig()->getAttribute('captcha');

        $this->assertEquals(100, $view->vars['width']);
        $this->assertEquals(30, $view->vars['height']);
        $this->assertStringStartsWith('data:image/png;base64,', $view->vars['src']);

        $this->assertEquals(4, $captcha->getLength());
    }

    public function testConfigs()
    {
        $form = $this->factory->create('genemu_captcha', null, array(
            'width' => 200,
            'font_color' => array('000'),
            'code' => '1111',
            'format' => 'gif',
        ));

        $view = $form->createView();
        $captcha = $form->getConfig()->getAttribute('captcha');

        $this->assertEquals(200, $view->vars['width']);
        $this->assertEquals(md5('1111s$cr$t'), $captcha->getCode());
        $this->assertStringStartsWith('data:image/gif;base64,', $view->vars['src']);
        $this->assertEquals(4, $captcha->getLength());
    }

    public function testFaultFonts()
    {
        try {
            $form = $this->factory->create('genemu_captcha', null, array(
                'fonts' => array('toto.ttf')
            ));
        } catch (FileNotFoundException $excepted) {
            $this->assertStringStartsWith('The file', $excepted->getMessage());
            $this->assertStringEndsWith('does not exist', $excepted->getMessage());

            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    public function testFaultFormat()
    {
        $form = $this->factory->create('genemu_captcha', null, array(
            'format' => 'bar'
        ));

        $view = $form->createView();

        $this->assertStringStartsWith('data:image/jpeg;base64,', $view->vars['src']);
    }

    public function testCodePasses()
    {
        $form = $this->factory->create('genemu_captcha');
        $form->createView();

        $form->bind('1234');

        $this->assertTrue($form->isValid());
    }

    public function testCodeFails()
    {
        $form = $this->factory->create('genemu_captcha');
        $form->createView();

        $form->bind('4321');

        $this->assertFalse($form->isValid());
    }
}
