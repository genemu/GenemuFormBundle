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

    protected $fontSizeSpreadingRange;
    protected $charsRotateRange;
    protected $charsPositionSpreadingRange;
    protected $charsSpacing;

    /**
     * Construct
     *
     * @param string $text
     * @param int    $fontSize
     * @param array  $fonts
     * @param array  $colors
     * @param array  $fontSizeSpreadingRange
     * @param array  $charsRotateRange
     * @param array  $charsPositionSpreadingRange
     * @param int    $charsSpacing
     */
    public function __construct($text, $fontSize = 12, array $fonts, array $colors, array $fontSizeSpreadingRange, array $charsRotateRange, array $charsPositionSpreadingRange, $charsSpacing)
    {
        $this->text = $text;
        $this->colors = $colors;
        $this->fontSize = $fontSize;
        $this->fontSizeSpreadingRange = $fontSizeSpreadingRange;
        $this->charsRotateRange = $charsRotateRange;
        $this->charsPositionSpreadingRange = $charsPositionSpreadingRange;
        $this->charsSpacing = $charsSpacing;

        if (count($charsRotateRange) !== 2) {
            throw new \InvalidArgumentException('Chars rotate range should contain 2 values');
        }

        if (count($fontSizeSpreadingRange) !== 2) {
            throw new \InvalidArgumentException('Font spreading range should contain 2 values');
        }

        if (count($charsPositionSpreadingRange) !== 2) {
            throw new \InvalidArgumentException('Chars position spreading range should contain 2 values');
        }

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
            $rotate = mt_rand($this->charsRotateRange[0], $this->charsRotateRange[1]);
            $size = $fs + $fs * (mt_rand($this->fontSizeSpreadingRange[0], $this->fontSizeSpreadingRange[1]) / 10);

            $font = $this->fonts[mt_rand(0, $nbF)];
            $color = $colors[mt_rand(0, $nbC)];

            $box = imagettfbbox($size, $rotate, $font, $this->text[$i]);

            $fw = max($box[2] - $box[0], $box[4] - $box[6]);

            $fh = max($box[1] - $box[7], $box[3] - $box[5]);
            $fh = $fh + ($h - $fh) / 2 + mt_rand($this->charsPositionSpreadingRange[0], $this->charsPositionSpreadingRange[1]);

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

        $x = ($w - $fwm) / 2 - ((count($texts) - 1) * $this->charsSpacing) / 2;
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

            $x += $text['x'] + $this->charsSpacing;
        }

        return $this->resource;
    }
}
