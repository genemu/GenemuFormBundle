<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Validator\Constraints;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Genemu\Bundle\FormBundle\Validator\Constraints\ReCaptcha;
use Genemu\Bundle\FormBundle\Validator\Constraints\ReCaptchaValidator;

/**
 * ReCaptchaValidatorTest
 *
 * @author Olivier Chauvel <olivier@gmail.com>
 */
class ReCaptchaValidatorTest extends WebTestCase
{
    protected $validator;

    protected function setUp()
    {
        parent::setup();
        
        $this->validator = new ReCaptchaValidator('6Ldv48YSAAAAABGLdVG__UD-JaMeqXMJ6SqV7dZ9');
    }
    
    protected function tearDown()
    {
        $this->validator = null;
    }
    
    public function testNullIsNotValid()
    {
        $this->assertFalse($this->validator->isValid(null, new ReCaptcha()), $this->validator->getMessageTemplate());
    }
    
    public function testEmptyStringIsNotValid()
    {
        $this->assertFalse($this->validator->isValid('', new ReCaptcha()), $this->validator->getMessageTemplate());
    }
}