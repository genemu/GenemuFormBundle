<?php
namespace Genemu\Bundle\FormBundle\Tests\Captcha;

use Genemu\Bundle\FormBundle\Captcha\CaptchaService;

class CaptchaServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function couldBeCreatedWithRightArguments()
    {
        new CaptchaService(
            $this->createCodeGeneratorMock(),
            $this->createFontResolverMock(),
            $this->createCaptchaStorageMock(),
            array()
        );
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Could not find captcha config "name"
     */
    public function shouldThrowIfCaptchaConfigNotExists()
    {
        $service = new CaptchaService(
            $this->createCodeGeneratorMock(),
            $this->createFontResolverMock(),
            $this->createCaptchaStorageMock(),
            array()
        );

        $service->generateCaptcha('name');
    }

    /**
     * @test
     */
    public function shouldGenerateCaptcha()
    {
        $generator = $this->createCodeGeneratorMock();
        $generator
            ->expects($this->once())
            ->method('generate')
        ;

        $fontResolver = $this->createFontResolverMock();
        $fontResolver
            ->expects($this->once())
            ->method('resolve')
        ;

        $storage = $this->createCaptchaStorageMock();
        $storage
            ->expects($this->once())
            ->method('setCode')
        ;

        $service = new CaptchaService(
            $generator,
            $fontResolver,
            $storage,
            array('name' => array(
                'fonts' => array('fontname'),
                'chars' => array(),
                'length' => 5,
            ))
        );

        $result = $service->generateCaptcha('name');

        $this->assertInstanceOf('Genemu\\Bundle\\FormBundle\\Gd\\Type\\Captcha', $result);
    }

    /**
     * @return array
     */
    public function nullDataProvider()
    {
        return array(
            array(null),
            array(false),
            array(''),
        );
    }

    /**
     * @test
     * @dataProvider nullDataProvider
     */
    public function shouldReturnFalseIfCodeEmpty($code)
    {
        $storage = $this->createCaptchaStorageMock();
        $storage
            ->expects($this->never())
            ->method('getCode')
        ;

        $service = new CaptchaService(
            $this->createCodeGeneratorMock(),
            $this->createFontResolverMock(),
            $storage,
            array()
        );

        $this->assertFalse($service->isCodeValid('name', $code));
    }

    /**
     * @test
     */
    public function shouldReturnTrueIfCodeValid()
    {
        $storage = $this->createCaptchaStorageMock();
        $storage
            ->expects($this->once())
            ->method('getCode')
            ->will($this->returnValue('code'))
        ;

        $service = new CaptchaService(
            $this->createCodeGeneratorMock(),
            $this->createFontResolverMock(),
            $storage,
            array()
        );

        $this->assertTrue($service->isCodeValid('name', 'code'));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Genemu\Bundle\FormBundle\Captcha\FontResolver
     */
    protected function createFontResolverMock()
    {
        return $this->getMock('Genemu\\Bundle\\FormBundle\\Captcha\\FontResolver', array(), array(), '', false);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Genemu\Bundle\FormBundle\Captcha\CodeGenerator
     */
    protected function createCodeGeneratorMock()
    {
        return $this->getMock('Genemu\\Bundle\\FormBundle\\Captcha\\CodeGenerator', array(), array(), '', false);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Genemu\Bundle\FormBundle\Captcha\CaptchaStorage
     */
    protected function createCaptchaStorageMock()
    {
        return $this->getMock('Genemu\\Bundle\\FormBundle\\Captcha\\CaptchaStorage', array(), array(), '', false);
    }
}