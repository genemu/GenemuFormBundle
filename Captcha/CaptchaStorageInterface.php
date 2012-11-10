<?php
namespace Genemu\Bundle\FormBundle\Captcha;

interface CaptchaStorageInterface
{
    /**
     * @param $code
     */
    public function setCode($code);

    /**
     * @return string
     */
    public function getCode();
}