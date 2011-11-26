<?php

/*
 * This file is part of the Genemu package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Gd\Filter;

use Genemu\Bundle\FormBundle\Gd\Gd;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class Text extends Gd implements Filter
{
    protected $text;
    protected $fontSize;

    protected $fonts;
    protected $colors;

    /**
     * Construct
     *
     * @param string $text
     * @param int    $fontSize
     * @param array  $fonts
     * @param array  $colors
     */
    public function __construct($text, $fontSize = 12, array $fonts, array $colors)
    {
        $this->text = $text;
        $this->colors = $colors;
        $this->fontSize = $fontSize;

        foreach ($fonts as $index => $font) {
            if (is_file($font)) {
                $this->fonts[] = $font;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $colors = $this->allocateColors($this->colors);

        $len = strlen($this->text);
        $nbF = count($this->fonts) - 1;
        $nbC = count($colors) - 1;

        $fs = $this->fontSize;

        $w = $this->width;
        $h = $this->height;

        $fwm = 0;
        $texts = array();
        for ($i = 0; $i < $len; ++$i) {
            $rotate = mt_rand(-25, 25);
            $size = $fs + $fs * (mt_rand(0, 3) / 10);

            $font = $this->fonts[mt_rand(0, $nbF)];
            $color = $colors[mt_rand(0, $nbC)];

            $box = imagettfbbox($size, $rotate, $font, $this->text[$i]);

            $fw = max($box[2] - $box[0], $box[4] - $box[6]);

            $fh = max($box[1] - $box[7], $box[3] - $box[5]);
            $fh = $fh + ($h - $fh) / 2 + mt_rand(-$h / 10, $h / 10);

            $texts[] = array(
                'value'  => $this->text[$i],
                'rotate' => $rotate,
                'size'   => $size,
                'font'   => $font,
                'color'  => $color,
                'x'      => $fw,
                'y'      => $fh
            );

            $fwm += $fw;
        }

        $x = ($w - $fwm) / 2;
        foreach ($texts as $text) {
            imagettftext(
                $this->resource,
                $text['size'],
                $text['rotate'],
                $x,
                $text['y'],
                $text['color'],
                $text['font'],
                $text['value']
            );

            $x += $text['x'];
        }

        return $this->resource;
    }
}
