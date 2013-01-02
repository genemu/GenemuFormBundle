<?php
namespace Genemu\Bundle\FormBundle\Tests\Captcha;

use Genemu\Bundle\FormBundle\Captcha\CaptchaStorage;

class CaptchaStorageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function couldBeConstructedWithRightArguments()
    {
        new CaptchaStorage($this->createSessionMock());
    }
    
    /**
     * @test
     */
    public function shouldSetCode()
    {
        $session = $this->createSessionMock();
        $session
            ->expects($this->once())
            ->method('set')
            ->with('genemu_form.captcha.codes', array('name' => 'code'))
        ;

        $storage = new CaptchaStorage($session);

        $storage->setCode('name', 'code');
    }

    /**
     * @test
     */
    public function shouldReturnEmptyStringIfCodeNotSet()
    {
        $session = $this->createSessionMock();
        $session
            ->expects($this->once())
            ->method('get')
            ->with('genemu_form.captcha.codes')
            ->will($this->returnValue(array()))
        ;

        $storage = new CaptchaStorage($session);
        $result = $storage->getCode('name');

        $this->assertInternalType('string', $result);
        $this->assertEmpty($result);
    }

    /**
     * @test
     */
    public function shouldReturnStoredCode()
    {
        $session = $this->createSessionMock();
        $session
            ->expects($this->once())
            ->method('get')
            ->with('genemu_form.captcha.codes')
            ->will($this->returnValue(array('name' => 'code')))
        ;

        $storage = new CaptchaStorage($session);

        $this->assertEquals('code', $storage->getCode('name'));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\HttpFoundation\Session\Session
     */
    protected function createSessionMock()
    {
        return $this->getMock('Symfony\\Component\\HttpFoundation\\Session\\Session', array(), array(), '', false);
    }
}
