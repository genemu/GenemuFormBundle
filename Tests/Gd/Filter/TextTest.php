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

use Symfony\Component\HttpFoundation\File\File;

use Genemu\Bundle\FormBundle\Gd\Filter\Text;
use Genemu\Bundle\FormBundle\Tests\Gd\TestCase;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class TextTest extends TestCase
{
    public function testDefault()
    {
        $filter = new Text('Foo', 12, array(new File(__DIR__ . '/../../Fixtures/fonts/akbar.ttf')), array('000'));
        $filter->setResource(imagecreatetruecolor(10, 10));

        $apply = $filter->apply();

        $this->assertTrue(is_resource($apply));
    }
}
