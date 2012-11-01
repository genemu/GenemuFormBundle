<?php
namespace Genemu\Bundle\FormBundle\Tests\Captcha;

use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;

use Genemu\Bundle\FormBundle\Captcha\CaptchaStorage;

class CaptchaStorageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function couldBeConstructedWithRightArguments()
    {
        $sessionStorage = new MockArraySessionStorage();
        $session = new Session($sessionStorage);

        new CaptchaStorage($this->createCodeEncoderMock(), $session);
    }
    
    /**
     * @test
     */
    public function shouldEncodeCodeAndSaveItInSession()
    {
        $sessionStorage = new MockArraySessionStorage();
        $session = new Session($sessionStorage);

        $encoder = $this->createCodeEncoderMock();
        $encoder
            ->expects($this->once())
            ->method('encode')
            ->with('code')
            ->will($this->returnValue('encoded-code'))
        ;

        $storage = new CaptchaStorage($encoder, $session);

        //guard
        $this->assertNull($session->get('genemu_form.captcha.code'));

        $storage->setCode('code');
        $this->assertEquals('encoded-code', $session->get('genemu_form.captcha.code'));
    }

    /**
     * @test
     */
    public function shouldReturnStoredCode()
    {
        $sessionStorage = new MockArraySessionStorage();
        $session = new Session($sessionStorage);
        $session->set('genemu_form.captcha.code', 'encoded-code');

        $encoder = $this->createCodeEncoderMock();
        $storage = new CaptchaStorage($encoder, $session);

        $this->assertEquals('encoded-code', $storage->getCode());
    }

    /**
     * @test
     */
    public function shouldRemoveStoredCode()
    {
        $sessionStorage = new MockArraySessionStorage();
        $session = new Session($sessionStorage);
        $session->set('genemu_form.captcha.code', 'encoded-code');

        $encoder = $this->createCodeEncoderMock();
        $storage = new CaptchaStorage($encoder, $session);

        $storage->removeCode();
        $this->assertNull($session->get('genemu_form.captcha.code'));
    }

    /**
     * @test
     */
    public function shouldReturnTrueIfCodeValid()
    {
        $sessionStorage = new MockArraySessionStorage();
        $session = new Session($sessionStorage);
        $session->set('genemu_form.captcha.code', 'encoded-code');

        $encoder = $this->createCodeEncoderMock();
        $encoder
            ->expects($this->any())
            ->method('encode')
            ->will($this->returnValue('encoded-code'))
        ;

        $storage = new CaptchaStorage($encoder, $session);

        $this->assertTrue($storage->isCodeValid('code'));
    }

    /**
     * @test
     */
    public function shouldReturnFalseIfCodeInvalid()
    {
        $sessionStorage = new MockArraySessionStorage();
        $session = new Session($sessionStorage);
        $session->set('genemu_form.captcha.code', 'encoded-code');

        $encoder = $this->createCodeEncoderMock();
        $encoder
            ->expects($this->any())
            ->method('encode')
            ->will($this->returnValue('invalid-encoded-code'))
        ;

        $storage = new CaptchaStorage($encoder, $session);

        $this->assertFalse($storage->isCodeValid('invalid-code'));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Genemu\Bundle\FormBundle\Captcha\CodeEncoderInterface
     */
    protected function createCodeEncoderMock()
    {
        return $this->getMock('Genemu\\Bundle\\FormBundle\\Captcha\\CodeEncoderInterface');
    }
}
