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

use Symfony\Component\HttpFoundation\Response;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
abstract class Image
{
    protected $img;

    protected $width;
    protected $height;
    protected $length;

    protected $background;
    protected $border;

    protected $fontSize;
    protected $fontColors;
    protected $fonts;

    protected $chars;

    protected $format;

    /**
     * Construct
     *
     * @param array  $options
     */
    public function __construct(array $options)
    {
        $this->width = $options['width'];
        $this->height = $options['height'];
        $this->length = $options['length'];

        $this->background = $options['background_color'];
        $this->border = $options['border_color'];

        $this->fonts = array();
        foreach ($options['fonts'] as $font) {
            if (!is_file($options['font_dir'].'/'.$font)) {
                throw new \Exception('Font '.$options['font_dir'].'/'.$font.' does not exists.');
            }

            $this->fonts[] = $options['font_dir'].'/'.$font;
        }

        $this->fontSize = array(
            'size' => $options['font_size'],
            'width' => imagefontwidth($options['font_size']) + $this->width / 30,
            'height' => imagefontheight($options['font_size'])
        );

        $this->fontColors = $options['font_color'];
        $this->format = $options['format'];
        $this->chars = $options['chars'];
    }

    /**
     * Get int width
     *
     * @return int $width
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Get int height
     *
     * @return int $height
     */
    public function getHeight()
    {
        return $this->height;
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
     * Get chars
     *
     * @return array $chars
     */
    public function getChars()
    {
        return $this->chars;
    }

    /**
     * Set width
     *
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * Set height
     *
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * Set length
     *
     * @param int $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * Get src image
     *
     * @return string
     */
    public function getSrc()
    {
        return 'data:image/'.$this->format.';base64,'.$this->generate();
    }

    /**
     * Get image clean
     *
     * @return string
     */
    protected function getImage()
    {
        $format = 'image'.$this->format;

        ob_start();
        $format($this->img);
        return base64_encode(ob_get_clean());
    }

    /**
     * Create a image
     */
    protected function create()
    {
        $this->img = imagecreatetruecolor($this->width, $this->height);

        $background = $this->allocateColor($this->background);
        $border = $this->allocateColor($this->border);

        imagefill($this->img, 0, 0, $background);
        imagerectangle($this->img, 0, 0, $this->width - 1, $this->height - 1, $border);

        $colors = array();
        foreach ($this->fontColors as $color) {
            $colors[] = $this->allocateColor($color);
        }
        $this->fontColors = $colors;

        return $this->img;
    }

    /**
     * Add strip to image
     *
     * @param int $nb
     */
    protected function addStrip($nb = 15)
    {
        if (!is_resource($this->img)) {
            throw new \Exception('Error resource does not exist.');
        }

        $nbColor = count($this->fontColors) - 1;

        for ($i = 0; $i < $nb; ++$i) {
            $x = mt_rand(0, $this->width);
            $y = mt_rand(0, $this->height);

            $x2 = $x + mt_rand(-$this->width / 3, $this->width / 3);
            $y2 = $y + mt_rand(-$this->height / 3, $this->height / 3);

            $color = $this->fontColors[mt_rand(0, $nbColor)];

            imageline($this->img, $x, $y, $x2, $y2, $color);
        }
    }

    /**
     * Add text to image
     *
     * @param string $txt
     */
    protected function addText($txt)
    {
        if (!is_resource($this->img)) {
            throw new \Exception('Error resource does not exist.');
        }

        $nbTxt = strlen($txt);
        $nbFont = count($this->fonts) - 1;
        $nbColor = count($this->fontColors) - 1;

        $fh = $this->fontSize['height'];
        $fw = $this->fontSize['width'];
        $fs = $this->fontSize['size'];

        for ($i = 0; $i < $nbTxt; ++$i) {
            $x = ($this->width * 0.95 - $nbTxt * $fw) / 2 + ($fw * $i);
            $y = $fh + ($this->height - $fh) / 2 + mt_rand(-$this->height / 10, $this->height / 10);

            $rotate = rand(-25, 25);

            $fontSize = $fs + $fs * (rand(0, 3) / 10);

            $font = $this->fonts[rand(0, $nbFont)];
            $color = $this->fontColors[rand(0, $nbColor)];

            imagettftext($this->img, $fontSize, $rotate, $x, $y, $color, $font, $txt[$i]);
        }
    }

    /**
     * Allocate color to image
     *
     * @param $img
     * @param string $color
     *
     * @return int
     */
    protected function allocateColor($color)
    {
        if (!is_resource($this->img)) {
            throw new \Exception('Error resource does not exist.');
        }

        return imagecolorallocate($this->img,
            hexdec(substr($color, 0, 2)),
            hexdec(substr($color, 2, 2)),
            hexdec(substr($color, 4, 2))
        );
    }

    /**
     * Generate image
     *
     * @return string
     */
    abstract protected function generate();
}
