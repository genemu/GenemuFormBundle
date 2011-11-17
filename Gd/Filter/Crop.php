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
class Crop extends Gd implements Filter
{
    protected $x;
    protected $y;
    protected $w;
    protected $h;

    /**
     * Construct
     *
     * @param int $x
     * @param int $y
     * @param int $w
     * @param int $h
     */
    public function __construct($x, $y, $w, $h)
    {
        $this->x = $x;
        $this->y = $y;
        $this->w = $w;
        $this->h = $h;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $tmp = $this->resource;

        $this->create($this->w, $this->h);

        imagecopy($this->resource, $tmp, 0, 0, $this->x, $this->y, $this->w, $this->h);
        imagedestroy($tmp);

        return $this->resource;
    }
}
