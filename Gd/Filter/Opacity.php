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
class Opacity extends Gd implements Filter
{
    protected $opacity;

    public function __construct($opacity)
    {
        $this->opacity = $opacity;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $tmp = $this->resource;

        $this->create($this->width, $this->height);

        $color = $this->allocateColor('FFF', $this->opacity);

        imagefill($this->resource, 0, 0 , $color);
        imagecopymerge($this->resource, $tmp, 0, 0, 0, 0, $this->width, $this->height, 75);
        imagedestroy($tmp);

        return $this->resource;
    }
}
