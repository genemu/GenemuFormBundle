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
class Strip extends Gd
{
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    public function apply(array $colors, $nb = 15)
    {
        $colors = $this->allocateColors($colors);

        $nbColor = count($colors) - 1;

        for ($i = 0; $i < $nb; ++$i) {
            $x = mt_rand(0, $this->width);
            $y = mt_rand(0, $this->height);

            $x2 = $x + mt_rand(-$this->width / 3, $this->width / 3);
            $y2 = $y + mt_rand(-$this->height / 3, $this->height / 3);

            $color = $colors[mt_rand(0, $nbColor)];

            imageline($this->resource, $x, $y, $x2, $y2, $color);
        }
    }
}
