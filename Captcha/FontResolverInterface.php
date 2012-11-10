<?php
namespace Genemu\Bundle\FormBundle\Captcha;

interface FontResolverInterface
{
    /**
     * @param string $font
     *
     * @return string
     */
    public function resolve($font);
}