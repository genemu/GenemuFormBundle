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
     * @var string
     */
    protected $scope = 'genemu_form.captcha.codes';

    /**
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param $name - captcha config name
     * @param $code
     */
    public function setCode($name, $code)
    {
        $data = $this->session->get($this->scope, array());

        $data[$name] = $code;

        $this->session->set($this->scope, $data);
    }

    /**
     * @param string $name - captcha config name
     *
     * @return string
     */
    public function getCode($name)
    {
        $data = $this->session->get($this->scope, array());

        return isset($data[$name]) ? $data[$name] : '';
    }
}