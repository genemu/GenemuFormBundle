<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Gd\File;

use Genemu\Bundle\FormBundle\Gd\File\Image;
use Genemu\Bundle\FormBundle\Gd\Filter\Background;

use Genemu\Bundle\FormBundle\Tests\Gd\TestCase;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class ImageTest extends TestCase
{
    public function testImage()
    {
        $image = new Image(__DIR__ . '/../../Fixtures/upload/symfony.png');

        $this->assertEquals(160, $image->getWidth());
        $this->assertEquals(134, $image->getHeight());
    }

    public function testBlackImage()
    {
        $image = new Image(__DIR__ . '/../../Fixtures/upload/black.png');

        $base64 = $image->getBase64();

        $this->assertEquals(16, $image->getWidth());
        $this->assertEquals(16, $image->getHeight());
        $this->assertEquals('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAACQkWg2AAAAEElEQVQokWNgGAWjYBTAAAADEAABC3uRhAAAAABJRU5ErkJggg==', $base64);
    }

    public function testChangeBackgroundBlackImage()
    {
        $image = new Image(__DIR__ . '/../../Fixtures/upload/black.png');
        $image->getGd()->addFilter(new Background('#FFF'));

        $base64 = $image->getBase64();

        $this->assertEquals(16, $image->getWidth());
        $this->assertEquals(16, $image->getHeight());
        $this->assertEquals('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAACQkWg2AAAAGUlEQVQokWP8//8/AymAiSTVoxpGNQwpDQBVbQMd5EZaEgAAAABJRU5ErkJggg==', $base64);
    }

    public function testCropImage()
    {
        $image = new Image(__DIR__ . '/../../Fixtures/upload/black.png');
        $image->addFilterCrop(0, 0, 5, 5);

        $base64 = $image->getBase64();

        $this->assertEquals(5, $image->getWidth());
        $this->assertEquals(5, $image->getHeight());
    }
}
