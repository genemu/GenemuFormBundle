<?php
namespace Genemu\Bundle\FormBundle\Captcha;

interface CodeGeneratorInterface
{
    /**
     * @param array $chars
     * @param $length
     *
     * @return string
     */
    public function generate(array $chars, $length);
}