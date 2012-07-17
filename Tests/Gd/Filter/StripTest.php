<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Gd\Filter;

use Genemu\Bundle\FormBundle\Gd\Filter\Strip;
use Genemu\Bundle\FormBundle\Tests\Gd\TestCase;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class StripTest extends TestCase
{
    public function testDefault()
    {
        $filter = new Strip(array('000', 'FFFFFF'));
        $filter->setResource(imagecreatetruecolor(10, 10));

        $apply = $filter->apply();

        $this->assertTrue(is_resource($apply));
    }
}
