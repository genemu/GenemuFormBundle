<?php

/*
 * This file is part of the Genemu package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Gd\Text;

use Symfony\Component\HttpFoundation\File\File;

use Genemu\Bundle\FormBundle\Gd\Gd;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class Text extends Gd
{
    protected $text;
    protected $fontSize;
    protected $fontWidth;
    protected $fontHeight;

    protected $length;

    public function __construct($resource, $text, $fontSize = 12)
    {
        parent::__construct($resource);

        $this->text = $text;
        $this->fontSize = $fontSize;
        $this->fontWidth = imagefontwidth($fontSize) + $this->width / 30;
        $this->fontHeight = imagefontheight($fontSize);

        $this->length = strlen($text);
    }

    public function getLength()
    {
        return $this->length;
    }

    public function apply(array $fonts, array $colors)
    {
        foreach ($fonts as $index => $font) {
            $fonts[$index] = new File($font);
        }

        $colors = $this->allocateColors($colors);

        $len = $this->length;
        $nbF = count($fonts) - 1;
        $nbC = count($colors) - 1;

        $fw = $this->fontWidth;
        $fh = $this->fontHeight;
        $fs = $this->fontSize;

        $w = $this->width;
        $h = $this->height;

        for ($i = 0; $i < $len; ++$i) {
            $x = ($w * 0.95 - $len * $fw) / 2 + ($fw * $i);
            $y = $fh + ($h - $fh) / 2 + mt_rand(-$h / 10, $h / 10);

            $r = rand(-25, 25);

            $s = $fs + $fs * (rand(0, 3) / 10);

            $f = $fonts[rand(0, $nbF)];
            $c = $colors[rand(0, $nbC)];

            imagettftext($this->resource, $s, $r, $x, $y, $c, $f->getPathname(), $this->text[$i]);
        }
    }
}
