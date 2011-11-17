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
class Colorize extends Gd implements Filter
{
    protected $color;

    /**
     * Construct
     *
     * @param string $color
     */
    public function __construct($color)
    {
        $this->color = $color;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        list($red, $green, $blue) = $this->hexColor($this->color);

        imagefilter($this->resource, IMG_FILTER_COLORIZE, $red, $green, $blue);

        return $this->resource;
    }
}
