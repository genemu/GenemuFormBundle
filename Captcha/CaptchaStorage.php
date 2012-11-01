<?php
namespace Genemu\Bundle\FormBundle\Captcha;

use Symfony\Component\HttpFoundation\Session\Session;

class CaptchaStorage
{
    /**
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    protected $session;

    /**
     * @var CodeEncoderInterface
     */
    protected $encoder;

    /**
     * @var string
     */
    protected $sessionCodeKey = 'genemu_form.captcha.code';

    /**
     * @var string
     */
    protected $sessionOptionsKey = 'genemu_form.captcha.options';

    /**
     * @param CodeEncoderInterface $encoder
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     */
    public function __construct(CodeEncoderInterface $encoder, Session $session)
    {
        $this->encoder      = $encoder;
        $this->session      = $session;
    }

    /**
     * Is code valid
     *
     * @param $code
     *
     * @return bool
     */
    public function isCodeValid($code)
    {
        return $this->encoder->encode($code) === $this->getCode();
    }

    /**
     * Set code
     *
     * @param string
     */
    public function setCode($code)
    {
        $this->session->set($this->sessionCodeKey, $this->encoder->encode($code));
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->session->get($this->sessionCodeKey);
    }

    /**
     * Remove code
     */
    public function removeCode()
    {
        $this->session->remove($this->sessionCodeKey);
    }
}