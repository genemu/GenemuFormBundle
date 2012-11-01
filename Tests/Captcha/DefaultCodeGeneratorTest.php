<?php
namespace Genemu\Bundle\FormBundle\Tests\Captcha;

use Genemu\Bundle\FormBundle\Captcha\DefaultCodeGenerator;

class DefaultCodeGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function couldBeConstructedWithoutArguments()
    {
        new DefaultCodeGenerator();
    }

    /**
     * @test
     */
    public function shouldGenerateCodeWithGivenLength()
    {
        $generator = new DefaultCodeGenerator();

        $this->assertEquals(1, strlen($generator->generate(array('a'), 1)));
        $this->assertEquals(5, strlen($generator->generate(array('a'), 5)));
        $this->assertEquals(10, strlen($generator->generate(array('a'), 10)));
    }

    /**
     * @test
     */
    public function shouldGenerateCodeWithSpecifiedChars()
    {
        $generator = new DefaultCodeGenerator();

        $this->assertEquals('aaaa', $generator->generate(array('a'), 4));
        $this->assertEquals('bbbbb', $generator->generate(array('b'), 5));
        $this->assertEquals('cccccc', $generator->generate(array('c'), 6));
    }
}