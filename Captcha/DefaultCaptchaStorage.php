<?php
namespace Genemu\Bundle\FormBundle\Captcha;

use Symfony\Component\HttpFoundation\Session\Session;

class DefaultCaptchaStorage implements CaptchaStorageInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    protected $session;

    /**
     * @var string
     */
    protected $sessionCodeKey = 'genemu_form.captcha.code';

    /**
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritDoc}
     */
    public function setCode($code)
    {
        $this->session->set($this->sessionCodeKey, $code);
    }

    /**
     * {@inheritDoc}
     */
    public function getCode()
    {
        return $this->session->get($this->sessionCodeKey);
    }
}