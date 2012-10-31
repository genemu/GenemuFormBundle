<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Gd\Type;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

use Genemu\Bundle\FormBundle\Gd\Gd;
use Genemu\Bundle\FormBundle\Gd\Filter\Text;
use Genemu\Bundle\FormBundle\Gd\Filter\Strip;
use Genemu\Bundle\FormBundle\Gd\Filter\Background;
use Genemu\Bundle\FormBundle\Gd\Filter\Border;
use Genemu\Bundle\FormBundle\Gd\Filter\GrayScale;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class Captcha extends Gd
{
    protected $session;
    protected $secret;
    protected $code;

    protected $width;
    protected $height;
    protected $format;

    protected $backgroundColor;
    protected $borderColor;
    protected $grayscale;

    protected $chars;
    protected $length;

    protected $fonts;
    protected $fontSize;
    protected $fontColor;

    private $key;

    /**
     * Construct
     *
     * @param Session $session
     * @param string  $secret
     */
    public function __construct(Session $session, $secret)
    {
        $this->session = $session;
        $this->secret = $secret;
        $this->key = 'genemu_form.captcha';
    }

    public function setOptions(array $options)
    {
        $defaultOptions = array(
            'width' => 100,
            'height' => 30,
            'format' => 'png',
            'background_color' => 'DDDDDD',
            'border_color' => '000000',
            'chars' => range(0, 9),
            'length' => 4,
            'fonts' => array(
                realpath(__DIR__ . '/../../Resources/public/fonts/akbar.ttf'),
                realpath(__DIR__ . '/../../Resources/public/fonts/brushcut.ttf'),
                realpath(__DIR__ . '/../../Resources/public/fonts/molten.ttf'),
                realpath(__DIR__ . '/../../Resources/public/fonts/planetbe.ttf'),
                realpath(__DIR__ . '/../../Resources/public/fonts/whoobub.ttf'),
            ),
            'font_size' => 16,
            'font_color' => array('252525', '8B8787', '550707', '3526E6', '88531E'),
            'grayscale' => false,
            'code' => null,
        );

        $options = array_replace($defaultOptions, $options);
        $options = array_intersect_key($options, $defaultOptions);

        foreach ($options as $key => $values) {
            $key = preg_replace_callback('/_([a-z])/', function($v) { return strtoupper($v[1]); }, $key);

            if ('fonts' === $key) {
                foreach ($values as $value) {
                    if (!is_file($value)) {
                        throw new FileNotFoundException($value);
                    }
                }
            }

            $this->$key = $values;
        }

        $this->session->set($this->key.'.options', $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getBase64($format = 'png')
    {
        $this->create($this->width, $this->height);

        $code = $this->newCode($this->chars, $this->length);

        $this->addFilters(array(
            new Background($this->backgroundColor),
            new Border($this->borderColor),
            new Strip($this->fontColor),
            new Text($code, $this->fontSize, $this->fonts, $this->fontColor),
        ));
        if ($this->grayscale) {
            $this->addFilter(new GrayScale());
        }

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

        if (!$this->code) {
            for ($i = 0; $i < $nb; ++$i) {
                $value.= $chars[array_rand($chars)];
            }

            $value = trim($value);
        } else {
            $value .= $this->code;
        }

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
