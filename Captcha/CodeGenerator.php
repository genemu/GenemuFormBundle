<?php
namespace Genemu\Bundle\FormBundle\Captcha;

class CodeGenerator
{
    /**
     * {@inheritDoc}
     */
    public function generate(array $chars, $length)
    {
        $code = '';

        for ($i = 0; $i < $length; ++$i) {
            $code .= $chars[array_rand($chars)];
        }

        return trim($code);
    }
}