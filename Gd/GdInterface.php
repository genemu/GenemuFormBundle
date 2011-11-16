<?php

/*
 * This file is part of the Genemu package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Gd;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
interface GdInterface
{
    public function getWidth();

    public function getHeight();

    public function getBase64($format = 'png');

    public function create($width, $height);

    public function reset();

    public function setResource($resource);

    public function allocateColors(array $colors);

    public function allocateColor($color);
}
