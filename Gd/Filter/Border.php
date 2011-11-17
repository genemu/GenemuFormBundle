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
class Border extends Gd implements Filter
{
    protected $color;
    protected $size;

    /**
     * Construct
     *
     * @param string $color
     * @param int    $size
     */
    public function __construct($color, $size = 1)
    {
        $this->color = $color;
        $this->size = $size;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $color = $this->allocateColor($this->color);

        $x = $y = $this->size - 1;
        $w = $this->width - $this->size;
        $h = $this->height - $this->size;

        imagerectangle($this->resource, $x, $y, $w, $h, $color);
        imagefilltoborder($this->resource, 0, 0, $color, $color);

        return $this->resource;
    }
}
