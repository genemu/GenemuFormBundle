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

use Genemu\Bundle\FormBundle\Tests\Form\Type\TypeTestCase;
use Genemu\Bundle\FormBundle\Form\Core\Type\ReCaptchaType;
use Genemu\Bundle\FormBundle\Form\Core\Validator\ReCaptchaValidator;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class ReCaptchaTypeTest extends TypeTestCase
{
    public function testDefaultConfigs()
    {
        $form = $this->factory->create('genemu_recaptcha');
        $view = $form->createView();

        $this->assertEquals('publicKey', $view->vars['public_key']);
        $this->assertEquals('http://www.google.com/recaptcha/api', $view->vars['server']);
        $this->assertEquals(array('lang' => 'en'), $view->vars['configs']);

        $this->assertEquals(array(
            'host' => 'www.google.com',
            'port' => 80,
            'path' => '/recaptcha/api/verify',
            'timeout' => 10,
        ), $form->getConfig()->getAttribute('option_validator'));
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

        $this->assertEquals('publicKey', $view->vars['public_key']);
        $this->assertEquals(array('theme' => 'blackglass', 'lang' => 'en'), $view->vars['configs']);

        $this->assertEquals(array(
            'host' => 'www.google.com',
            'port' => 80,
            'path' => '/recaptcha/api/verify',
            'timeout' => 30
        ), $form->getConfig()->getAttribute('option_validator'));
    }
    
    /**
     * @dataProvider provideCodes
     */
    public function testCode($code, $isValid)
    {
        $request = new Request(array(), array('recaptcha_response_field' => $code));
        $form = $this->factory->create(new ReCaptchaType(
            new ReCaptchaValidator($request, 'privateKey', array('code' => '1234')),
            'publicKey',
            'http://www.google.com/recaptcha/api',
            array()
        ));

        $form->bind(null);

        $this->assertEquals($isValid, $form->isValid());
    }

    public function provideCodes()
    {
        return array(
            array('1234', true),
            array('4321', false),
        );
    }
}
