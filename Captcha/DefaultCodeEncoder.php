<?php
namespace Genemu\Bundle\FormBundle\Captcha;

class DefaultCodeEncoder implements CodeEncoderInterface
{
    /**
     * @var string
     */
    protected $secret;

    /**
     * @param string $secret
     */
    public function __construct($secret = '')
    {
        $this->secret = $secret;
    }

    /**
     * {@inheritDoc}
     */
    public function encode($code)
    {
        return md5($code . $this->secret);
    }
}