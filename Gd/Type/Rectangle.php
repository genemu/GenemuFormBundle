<?php

/*
 * This file is part of the Genemu package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Gd\Type;

use Genemu\Bundle\FormBundle\Gd\Gd;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class Rectangle extends Gd
{
    public function __construct($width, $height, $background = '000000', $border = null, $borderSize = 1)
    {
        parent::__construct(imagecreatetruecolor($width, $height));

        $this->addBackground($background);

        if ($border) {
            $this->addBorder($border, $borderSize);
        }
    }
}
