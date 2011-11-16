<?php

/*
 * This file is part of the Genemu package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Gd\File;

use Symfony\Component\HttpFoundation\File\File;

use Genemu\Bundle\FormBundle\Gd\Gd;
use Genemu\Bundle\FormBundle\Gd\Filter\Rotate;
use Genemu\Bundle\FormBundle\Gd\Filter\Negate;
use Genemu\Bundle\FormBundle\Gd\Filter\Colorize;
use Genemu\Bundle\FormBundle\Gd\Filter\GrayScale;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class Image extends File
{
    protected $gd;

    /**
     * Construct
     *
     * @param string $path
     */
    public function __construct($path, $checkPath = true)
    {
        parent::__construct($path, $checkPath);

        if (!preg_match('/image/', $this->getMimeType())) {
            throw new \Exception('Is not a image file.');
        }

        $type = 'imagecreatefrom'.('jpg' === $this->guessExtension() ) ? 'jpeg' : $this->guessExtension();
        if (!function_exists($type)) {
            $type = 'imagecreatefromjpeg';
        }

        $this->gd = new Gd($type($this->getPathname()));
    }

    public function rotate($rotate = 90)
    {
        $this->gd->addFilter(new Rotate($rotate));
    }

    public function negate()
    {
        $this->gd->addFilter(new Negate());
    }

    public function sepia($color)
    {
        $this->gd->addFilters(array(
            new GrayScale(),
            new Colorize($color)
        ));
    }

    public function grayScale()
    {
        $this->gd->addFilter(new GrayScale());
    }

    public function getWidth()
    {
        return $this->gd->getWidth();
    }

    public function getHeight()
    {
        return $this->gd->getHeight();
    }

    public function save($quality = 100)
    {
        $this->gd->save($this->getPathname(), $this->guessExtension(), $quality);
    }
}
