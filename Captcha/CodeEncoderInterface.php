<?php
namespace Genemu\Bundle\FormBundle\Captcha;

interface CodeEncoderInterface
{
    /**
     * @param string $code
     */
    public function encode($code);
}