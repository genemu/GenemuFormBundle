<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Gd;

use Genemu\Bundle\FormBundle\Gd\Gd;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class GdTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();

        if (!function_exists('gd_info')) {
            $this->markTestSkipped('Gd not installed');
        }
    }

    public function testHexToSimpleColor()
    {
        $gd = new Gd();

        $color1 = '#000';
        $color2 = 'F00';
        $color3 = '#0F0';
        $color4 = 'FF0';
        $color5 = '#0FF';
        $color6 = 'F0F';
        $color7 = '#FFF';
        $color8 = 'D5F';

        $this->assertEquals(array(0, 0, 0), $gd->hexColor($color1));
        $this->assertEquals(array(255, 0, 0), $gd->hexColor($color2));
        $this->assertEquals(array(0, 255, 0), $gd->hexColor($color3));
        $this->assertEquals(array(255, 255, 0), $gd->hexColor($color4));
        $this->assertEquals(array(0, 255, 255), $gd->hexColor($color5));
        $this->assertEquals(array(255, 0, 255), $gd->hexColor($color6));
        $this->assertEquals(array(255, 255, 255), $gd->hexColor($color7));
        $this->assertEquals(array(221, 85, 255), $gd->hexColor($color8));
    }

    public function testHexToComplexeColor()
    {
        $gd = new Gd();

        $color1 = '#F5AB48';
        $color2 = '0F7A4F';

        $this->assertEquals(array(245, 171, 72), $gd->hexColor($color1));
        $this->assertEquals(array(15, 122, 79), $gd->hexColor($color2));
    }

    public function testHexToString()
    {
        $gd = new Gd();

        $color1 = '#F5AB48';
        $color2 = '0F7A4F';

        $this->assertEquals('245,171,72', $gd->hexColor($color1, true));
        $this->assertEquals('15-122-79', $gd->hexColor($color2, true, '-'));
    }

    public function testCreateResource()
    {
        $gd = new Gd();
        $gd->create(100, 100);

        try {
            $gd->checkResource();
        } catch (\Exception $e) {
            $this->fail('Resource does not create.');
        }

        $this->assertEquals(100, $gd->getWidth());
        $this->assertEquals(100, $gd->getHeight());

        $this->assertEquals('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAIAAAD/gAIDAAAANElEQVR4nO3BAQ0AAADCoPdPbQ43oAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAfgx1lAABqFDyOQAAAABJRU5ErkJggg==', $gd->getBase64());
    }

    public function testCheckFormat()
    {
        $gd = new Gd();

        $this->assertEquals('png', $gd->checkFormat('png'));
        $this->assertEquals('jpeg', $gd->checkFormat('jpeg'));
        $this->assertEquals('gif', $gd->checkFormat('gif'));
        $this->assertEquals('jpeg', $gd->checkFormat('toto'));
    }
}
