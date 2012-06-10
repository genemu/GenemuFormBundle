<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Form\Core\Type;

use Genemu\Bundle\FormBundle\Tests\Form\Type\TypeTestCase;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class ReCaptchaTypeTest extends TypeTestCase
{
    public function testDefaultConfigs()
    {
        $form = $this->factory->create('genemu_recaptcha');
        $view = $form->createView();

        $this->assertEquals('publicKey', $view->getVar('public_key'));
        $this->assertEquals('http://api.recaptcha.net', $view->getVar('server'));
        $this->assertEquals(array('lang' => 'en'), $view->getVar('configs'));

        $this->assertEquals(array(
            'host' => 'api-verify.recaptcha.net',
            'port' => 80,
            'path' => '/verify',
            'timeout' => 10,
        ), $form->getAttribute('option_validator'));
    }

    public function testConfigs()
    {
        $form = $this->factory->create('genemu_recaptcha', null, array(
            'configs' => array(
                'theme' => 'blackglass',
            ),
            'validator' => array('timeout' => 30),
        ));
        $view = $form->createView();

        $this->assertEquals('publicKey', $view->getVar('public_key'));
        $this->assertEquals(array('theme' => 'blackglass', 'lang' => 'en'), $view->getVar('configs'));

        $this->assertEquals(array(
            'host' => 'api-verify.recaptcha.net',
            'port' => 80,
            'path' => '/verify',
            'timeout' => 30
        ), $form->getAttribute('option_validator'));
    }
}
