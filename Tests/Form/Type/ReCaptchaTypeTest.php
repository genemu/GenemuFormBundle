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
class ReCaptchaTypeTest extends TypeTestCase
{
    public function testDefaultConfigs()
    {
        $form = $this->factory->create('genemu_recaptcha');
        $view = $form->createView();

        $this->assertEquals('publicKey', $view->get('public_key'));
        $this->assertEquals('http://api.recaptcha.net', $view->get('server'));
        $this->assertEquals(array('theme' => 'clean', 'lang' => 'de_DE'), $view->get('configs'));

        $this->assertEquals(array(
            'server_host' => 'api-verify.recaptcha.net',
            'server_port' => 80,
            'server_path' => '/verify',
            'server_timeout' => 10
        ), $form->getAttribute('option_validator'));
    }

    public function testConfigs()
    {
        $form = $this->factory->create('genemu_recaptcha', null, array(
            'theme' => 'blackglass',
            'server_timeout' => 30,
            'use_ssl' => true
        ));
        $view = $form->createView();

        $this->assertEquals('publicKey', $view->get('public_key'));
        $this->assertEquals('https://api-secure.recaptcha.net', $view->get('server'));
        $this->assertEquals(array('theme' => 'blackglass', 'lang' => 'de_DE'), $view->get('configs'));

        $this->assertEquals(array(
            'server_host' => 'api-verify.recaptcha.net',
            'server_port' => 80,
            'server_path' => '/verify',
            'server_timeout' => 30
        ), $form->getAttribute('option_validator'));
    }
}
