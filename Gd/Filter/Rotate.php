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
class Rotate extends Gd implements Filter
{
    protected $rotate;

    /**
     * Construct
     *
     * @param int $rotate
     */
    public function __construct($rotate)
    {
        $this->rotate = $rotate;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        return $this->resource = imagerotate($this->resource, $this->rotate, 0);
    }
}
