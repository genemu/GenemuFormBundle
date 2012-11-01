<?php
namespace Genemu\Bundle\FormBundle\Captcha;

class DefaultCodeGenerator implements CodeGeneratorInterface
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