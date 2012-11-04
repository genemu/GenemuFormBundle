<?php
namespace Genemu\Bundle\FormBundle\Captcha;

interface FontResolverInterface
{
    /**
     * @param array $fonts
     *
     * @return array
     */
    public function resolve(array $fonts);
}