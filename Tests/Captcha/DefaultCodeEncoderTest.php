<?php
namespace Genemu\Bundle\FormBundle\Tests\Captcha;

use Genemu\Bundle\FormBundle\Captcha\DefaultCodeEncoder;

class DefaultCodeEncoderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function couldBeConstructedWithoutArguments()
    {
        new DefaultCodeEncoder();
    }

    /**
     * @test
     */
    public function shouldEncodeCodeWithoutSecret()
    {
        $encoder = new DefaultCodeEncoder();

        $this->assertEquals('c13367945d5d4c91047b3b50234aa7ab', $encoder->encode('code'));
    }

    /**
     * @test
     */
    public function shouldEncodeCodeWithSecret()
    {
        $encoder = new DefaultCodeEncoder('secret');

        $this->assertEquals('3067debd90ac6077f90b6998bea8c8f0', $encoder->encode('code'));
    }
}