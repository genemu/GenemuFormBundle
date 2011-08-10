<?php

namespace Genemu\Bundle\FormBundle\Tests\Validator\Constraints;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Genemu\Bundle\FormBundle\Validator\Constraints\ReCaptcha;
use Genemu\Bundle\FormBundle\Validator\Constraints\ReCaptchaValidator;

class ReCaptchaValidatorTest extends WebTestCase
{
    protected $validator;

    protected function setUp()
    {
        parent::setup();
        
        $this->validator = new ReCaptchaValidator('6Ldv48YSAAAAABGLdVG__UD-JaMeqXMJ6SqV7dZ9');
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