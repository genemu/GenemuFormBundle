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
class Background extends Gd implements Filter
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
        $color = $this->allocateColor($this->color);

        imagefill($this->resource, 0, 0, $color);

        return $this->resource;
    }
}
