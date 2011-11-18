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
use Symfony\Component\HttpFoundation\File\File;

use Genemu\Bundle\FormBundle\Gd\Gd;
use Genemu\Bundle\FormBundle\Gd\Filter\Text;
use Genemu\Bundle\FormBundle\Gd\Filter\Strip;
use Genemu\Bundle\FormBundle\Gd\Filter\Background;
use Genemu\Bundle\FormBundle\Gd\Filter\Border;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class Captcha extends Gd
{
    protected $session;
    protected $secret;

    protected $background;
    protected $border;

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
        $this->create($options['width'], $options['height']);

        $this->session = $session;
        $this->secret = $secret;
        $this->key = 'genemu_captcha';

        $this->background = $options['background_color'];
        $this->border = $options['border_color'];

        $this->fonts = array();
        foreach ($options['fonts'] as $font) {
            $this->fonts[] = new File($options['font_dir'].'/'.$font);
        }

        $this->chars = $options['chars'];
        $this->length = $options['length'];
        $this->fontSize = $options['font_size'];
        $this->fontColor = $options['font_color'];
    }

    /**
     * {@inheritdoc}
     */
    public function getBase64($format = 'png')
    {
        $code = $this->newCode(str_split($this->chars), $this->length);

        $this->addFilters(array(
            new Background($this->background),
            new Border($this->border),
            new Text($code, $this->fontSize, $this->fonts, $this->fontColor),
            new Strip($this->fontColor)
        ));

        return parent::getBase64($format);
    }

    /**
     * Get length
     *
     * @return int $length
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Create a new code
     *
     * @param array $chars
     * @param int   $nb
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
