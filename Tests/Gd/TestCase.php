<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Gd;

/**
 * @author Leszek Prabucki <leszek.prabucki@gmail.com>
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();

        if (!function_exists('gd_info') || !function_exists('imagettfbbox')) {

            $this->markTestSkipped('Gd with freetype not installed');
        }
    }
}
