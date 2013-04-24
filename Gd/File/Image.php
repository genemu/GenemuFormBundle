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

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;

use Genemu\Bundle\FormBundle\Gd\Gd;
use Genemu\Bundle\FormBundle\Gd\Filter\Crop;
use Genemu\Bundle\FormBundle\Gd\Filter\Rotate;
use Genemu\Bundle\FormBundle\Gd\Filter\Negate;
use Genemu\Bundle\FormBundle\Gd\Filter\Colorize;
use Genemu\Bundle\FormBundle\Gd\Filter\GrayScale;
use Genemu\Bundle\FormBundle\Gd\Filter\Blur;
use Genemu\Bundle\FormBundle\Gd\Filter\Opacity;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class Image extends File
{
    protected $gd = null;

    /**
     * {@inheritdoc}
     */
    public function __construct($path, $checkPath = true)
    {
        parent::__construct($path, $checkPath);

        if (false === strpos($this->getMimeType(), 'image')) {
            throw new \Exception(sprintf('Is not an image file. (%s)', $this->getMimeType()));
        }
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
     * Create thumbnail image
     *
     * @param string $name
     * @param int    $width
     * @param int    $height
     */
    public function createThumbnail($name, $width, $height, $quality = 90)
    {
        $ext = $this->guessExtension();

        $path  = $this->getPath() . '/';
        $path .= $this->getBasename('.' . $ext) . $name;
        $path .= '.' . $ext;

        $thumbnail = $this->getGd()->createThumbnail($name, $path, $width, $height, $ext, $quality);

        $this->getGd()->setThumbnail($name, new Image($thumbnail->getPathname()));
    }

    /**
     * Search thumbnails
     */
    public function searchThumbnails()
    {
        $thumbnails = array();

        $fileExt = $this->guessExtension();
        $fileName = $this->getBasename('.' . $fileExt);

        $files = new Finder();
        $files
            ->in($this->getPath())
            ->name($fileName . '*.' . $fileExt)
            ->notName($this->getFilename())
            ->files();

        foreach ($files as $file) {
            $file = new Image($file->getPathname());
            $thumbnail = preg_replace('/^' . $fileName . '(\w+)(.*)/', '$1', $file->getFilename());

            $thumbnails[$thumbnail] = $file;
        }

        $this->getGd()->setThumbnails($thumbnails);
    }

    /**
     * Get thumbnail
     *
     * @param string $name
     *
     * @return Image|null
     */
    public function getThumbnail($name)
    {
        if (!$this->hasThumbnail($name)) {
            $this->searchThumbnails();
        }

        return $this->getGd()->getThumbnail($name);
    }

    /**
     * Get thumbnails
     *
     * @return array
     */
    public function getThumbnails()
    {
        if (!$this->getGd()->getThumbnails()) {
            $this->searchThumbnails();
        }

        return $this->getGd()->getThumbnails();
    }

    /**
     * Has thumbnail
     *
     * @param string $name
     *
     * @return boolean
     */
    public function hasThumbnail($name)
    {
        if (!$this->getGd()->getThumbnails()) {
            $this->searchThumbnails();
        }

        return $this->getGd()->hasThumbnail($name);
    }

    /**
     * Add filter crop to image
     *
     * @param int $x
     * @param int $y
     * @param int $w
     * @param int $h
     */
    public function addFilterCrop($x, $y, $w, $h)
    {
        $this->getGd()->addFilter(new Crop($x, $y, $w, $h));
    }

    /**
     * Add filter rotate to image
     *
     * @param int $rotate
     */
    public function addFilterRotate($rotate = 90)
    {
        $this->getGd()->addFilter(new Rotate($rotate));
    }

    /**
     * Add filter negative to image
     */
    public function addFilterNegative()
    {
        $this->getGd()->addFilter(new Negate());
    }

    /**
     * Add filter sepia to image
     *
     * @param string $color
     */
    public function addFilterSepia($color)
    {
        $this->getGd()->addFilters(array(
            new GrayScale(),
            new Colorize($color)
        ));
    }

    /**
     * Add filter gray scale to image
     */
    public function addFilterBw()
    {
        $this->getGd()->addFilter(new GrayScale());
    }

    /**
     * Add filter blur to image
     */
    public function addFilterBlur()
    {
        $this->getGd()->addFilter(new Blur());
    }

    /**
     * Add filter opacity to image
     */
    public function addFilterOpacity($opacity)
    {
        $this->getGd()->addFilter(new Opacity($opacity));
    }

    /**
     * Get gd manipulator
     *
     * @return \Genemu\Bundle\FormBundle\Gd\Gd
     */
    public function getGd()
    {
        if (is_null($this->gd)) {
            $format = $this->checkFormat($this->guessExtension());
            $generate = 'imagecreatefrom' . $format;

            $this->gd = new Gd();
            $this->gd->setResource($generate($this->getPathname()));
        }

        return $this->gd;
    }

    /**
     * Get width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->getGd()->getWidth();
    }

    /**
     * Get height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->getGd()->getHeight();
    }

    /**
     * Get base64 image
     *
     * @return string
     */
    public function getBase64()
    {
        return $this->getGd()->getBase64($this->guessExtension());
    }

    /**
     * Save image file
     *
     * @param int $quality
     */
    public function save($quality = 90)
    {
        $this->getGd()->save($this->getPathname(), $this->guessExtension(), $quality);
    }
}
