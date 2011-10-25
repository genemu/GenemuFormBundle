<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Gd;

use Symfony\Component\HttpFoundation\Session;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class Captcha extends Image
{
    protected $session;
    protected $secret;

    private $key;

    /**
     * Construct
     *
     * @param Session $session
     * @param string  $secret
     * @param array   $options
     */
    public function __construct(Session $session, $secret, array $options)
    {
        $this->session = $session;
        $this->secret = $secret;
        $this->key = 'genemu_captcha';

        parent::__construct($options);
    }

    /**
     * {@inheritdoc}
     */
    protected function generate()
    {
        $code = $this->newCode();

        $this->setCode($code);

        $img = $this->create();
        $this->addStrip();
        $this->addText($code);

        return $this->getImage();
    }

    /**
     * Create a new code
     *
     * @return string
     */
    protected function newCode()
    {
        $value = '';
        $chars = str_split($this->getChars());
        $length = $this->getLength();

        for ($i = 0; $i < $length; ++$i) {
            $value.= $chars[array_rand($chars)];
        }

        return trim($value);
    }

    /**
     * Set code
     *
     * @param string
     */
    public function setCode($code)
    {
        $this->session->set($this->key, $this->encode($code));
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->session->get($this->key);
    }

    /**
     * Remove code
     */
    public function removeCode()
    {
        $this->session->remove($this->key);
    }

    /**
     * Encode a new code
     *
     * @param string $code
     *
     * @return string
     */
    public function encode($code)
    {
        return md5($code.$this->secret);
    }
}
