<?php
namespace Genemu\Bundle\FormBundle\Tests\Captcha;

use Genemu\Bundle\FormBundle\Captcha\DefaultCaptchaStorage;

class DefaultStorageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function couldBeConstructedWithRightArguments()
    {
        new DefaultCaptchaStorage($this->createSessionMock());
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
            ->with('genemu_form.captcha.code', 'code')
        ;

        $storage = new DefaultCaptchaStorage($session);

        $storage->setCode('code');
    }

    /**
     * @test
     */
    public function couldGetCode()
    {
        $session = $this->createSessionMock();
        $session
            ->expects($this->once())
            ->method('get')
            ->with('genemu_form.captcha.code')
            ->will($this->returnValue('code'))
        ;

        $storage = new DefaultCaptchaStorage($session);

        $this->assertEquals('code', $storage->getCode());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\HttpFoundation\Session\Session
     */
    protected function createSessionMock()
    {
        return $this->getMock('Symfony\\Component\\HttpFoundation\\Session\\Session', array(), array(), '', false);
    }
}
