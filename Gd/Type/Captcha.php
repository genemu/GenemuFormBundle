<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Gd\Type;

use Symfony\Component\HttpFoundation\Session;

use Genemu\Bundle\FormBundle\Gd\Text\Text;
use Genemu\Bundle\FormBundle\Gd\Filter\Strip;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class Captcha extends Rectangle
{
    protected $session;
    protected $secret;

    protected $text;
    protected $strip;

    protected $fonts;
    protected $chars;
    protected $length;

    protected $fontSize;
    protected $fontColor;

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

        parent::__construct(
            $options['width'],
            $options['height'],
            $options['background_color'],
            $options['border_color']
        );

        $this->fonts = array();
        foreach ($options['fonts'] as $font) {
            $this->fonts[] = $options['font_dir'].'/'.$font;
        }

        $this->chars = $options['chars'];
        $this->length = $options['length'];
        $this->fontSize = $options['font_size'];
        $this->fontColor = $options['font_color'];
    }

    public function getBase64($format = 'png')
    {
        $code = $this->newCode(str_split($this->chars), $this->length);

        $this->text = new Text($this->resource, $code, $this->fontSize);
        $this->text->apply($this->fonts, $this->fontColor);

        $this->strip = new Strip($this->resource);
        $this->strip->apply($this->fontColor);

        return parent::getBase64($format);
    }

    public function getLength()
    {
        return $this->length;
    }

    /**
     * Create a new code
     *
     * @return string
     */
    protected function newCode(array $chars, $nb)
    {
        $value = '';

        for ($i = 0; $i < $nb; ++$i) {
            $value.= $chars[array_rand($chars)];
        }

        $value = trim($value);

        $this->setCode($value);

        return $value;
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
