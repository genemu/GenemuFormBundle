<?php
namespace Genemu\Bundle\FormBundle\Tests\Captcha;

use Genemu\Bundle\FormBundle\Captcha\CodeGenerator;

class CodeGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function couldBeConstructedWithoutArguments()
    {
        new CodeGenerator();
    }

    /**
     * @test
     */
    public function shouldGenerateCodeWithGivenLength()
    {
        $generator = new CodeGenerator();

        $this->assertEquals(1, strlen($generator->generate(array('a'), 1)));
        $this->assertEquals(5, strlen($generator->generate(array('a'), 5)));
        $this->assertEquals(10, strlen($generator->generate(array('a'), 10)));
    }

    /**
     * @test
     */
    public function shouldGenerateCodeWithSpecifiedChars()
    {
        $generator = new CodeGenerator();

        $this->assertEquals('aaaa', $generator->generate(array('a'), 4));
        $this->assertEquals('bbbbb', $generator->generate(array('b'), 5));
        $this->assertEquals('cccccc', $generator->generate(array('c'), 6));
    }
}