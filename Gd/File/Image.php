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
     * {@inheritdoc}
     */
    public function __construct($path, $checkPath = true)
    {
        parent::__construct($path, $checkPath);

        if (!preg_match('/image/', $this->getMimeType())) {
            throw new \Exception('Is not a image file.');
        }

        $format = $this->checkFormat($this->guessExtension());
        $generate = 'imagecreatefrom'.$format;

        $this->gd = new Gd($generate($this->getPathname()));
    }

    /**
     * Check format image
     *
     * @param string $format
     */
    public function checkFormat($format)
    {
        $function = 'imagecreatefrom'.$format;

        if (!function_exists($function)) {
            return $this->checkFormat('jpeg');
        }

        return $format;
    }

    /**
     * Add filter rotate to image
     *
     * @param int $rotate
     */
    public function rotate($rotate = 90)
    {
        $this->gd->addFilter(new Rotate($rotate));
    }

    /**
     * Add filter negate to image
     */
    public function negate()
    {
        $this->gd->addFilter(new Negate());
    }

    /**
     * Add filter sepia to image
     *
     * @param string $color
     */
    public function sepia($color)
    {
        $this->gd->addFilters(array(
            new GrayScale(),
            new Colorize($color)
        ));
    }

    /**
     * Add filter gray scale to image
     */
    public function grayScale()
    {
        $this->gd->addFilter(new GrayScale());
    }

    /**
     * Get width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->gd->getWidth();
    }

    /**
     * Get height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->gd->getHeight();
    }

    /**
     * Save image file
     *
     * @param int $quality
     */
    public function save($quality = 100)
    {
        $this->gd->save($this->getPathname(), $this->guessExtension(), $quality);
    }
}
