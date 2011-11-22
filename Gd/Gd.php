<?php

/*
 * This file is part of the Genemu package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Gd;

use Symfony\Component\HttpFoundation\File\File;

use Genemu\Bundle\FormBundle\Gd\Filter\Filter;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class Gd implements GdInterface
{
    protected $resource;

    protected $filters = array();
    protected $thumbnails = array();

    protected $width;
    protected $height;

    /**
     * {@inheritdoc}
     */
    public function checkFormat($format)
    {
        $function = 'image'.$format;

        if (!function_exists($function)) {
            return $this->checkFormat('jpeg');
        }

        return $format;
    }

    /**
     * {@inheritdoc}
     */
    public function checkResource()
    {
        if (!is_resource($this->resource)) {
            throw new \Exception('Resource does not exists.');
        }
    }

    /**
     * Create thumbnail
     *
     * @param string $name
     * @param string $path
     * @param int    $width
     * @param int    $height
     * @param string $format
     */
    public function createThumbnail($name, $path, $width, $height, $format = 'png', $quality = 90)
    {
        $width_tmp = $width;
        $height_tmp = ($width / $this->width) * $this->height;

        if ($height_tmp > $height) {
            $height_tmp = $height;
            $width_tmp = ($height / $this->height) * $this->width;
        }

        $tmp = imagecreatetruecolor($width_tmp, $height_tmp);

        imagecopyresampled($tmp, $this->resource, 0, 0, 0, 0, $width_tmp, $height_tmp, $this->width, $this->height);

        $format = $this->checkFormat($format);
        $generate = 'image'.$format;

        $generate($tmp, $path, $quality);

        return $this->thumbnails[$name] = new File($path);
    }

    /**
     * Set thumbnails
     *
     * @param array $thumbnails
     */
    public function setThumbnails(array $thumbnails)
    {
        foreach ($thumbnails as $name => $thumbnail) {
            $this->setThumbnail($name, $thumbnail);
        }
    }

    /**
     * Set thumbnail
     *
     * @param string $name
     * @param File   $thumbnail
     */
    public function setThumbnail($name, File $thumbnail)
    {
        $this->thumbnails[$name] = $thumbnail;
    }

    /**
     * Get thumbnail
     *
     * @param string $name
     *
     * @return File|Image|null
     */
    public function getThumbnail($name)
    {
        if ($this->hasThumbnail($name)) {
            return $this->thumbnails[$name];
        }

        return null;
    }

    /**
     * Get thumbnails
     *
     * @return array $thumbnails
     */
    public function getThumbnails()
    {
        return $this->thumbnails;
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
        return isset($this->thumbnails[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * {@inheritdoc}
     */
    public function getBase64($format = 'png')
    {
        $this->checkResource();

        $format = $this->checkFormat($format);
        $generate = 'image'.$format;

        $this->applyFilters();

        ob_start();
        $generate($this->resource);

        return 'data:image/'.$format.';base64,'.base64_encode(ob_get_clean());
    }

    /**
     * {@inheritdoc}
     */
    public function save($path, $format = 'png', $quality = 100)
    {
        $this->checkResource();

        $format = $this->checkFormat($format);
        $generate = 'image'.$format;

        $this->applyFilters();

        $generate($this->resource, $path, $quality);
    }

    /**
     * {@inheritdoc}
     */
    public function addFilter(Filter $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * {@inheritdoc}
     */
    public function addFilters(array $filters)
    {
        foreach ($filters as $filter) {
            $this->addFilter($filter);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function applyFilters()
    {
        $this->checkResource();

        foreach ($this->filters as $filter) {
            $filter->setResource($this->resource);

            $this->setResource($filter->apply());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function create($width, $height)
    {
        $this->setResource(imagecreatetruecolor($width, $height));
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $this->create($this->width, $this->height);
    }

    /**
     * {@inheritdoc}
     */
    public function setResource($resource)
    {
        if (!is_resource($resource)) {
            throw new \Exception('Resource does not exists.');
        }

        $this->resource = $resource;
        $this->width = imagesx($resource);
        $this->height = imagesy($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function allocateColors(array $colors)
    {
        $array = array();
        foreach ($colors as $color) {
            $array[] = $this->allocateColor($color);
        }

        return $array;
    }

    /**
     * {@inheritdoc}
     */
    public function allocateColor($color)
    {
        $this->checkResource();

        list($red, $green, $blue) = $this->hexColor($color);

        return imagecolorallocate($this->resource, $red, $green, $blue);
    }

    /**
     * {@inheritdoc}
     */
    public function hexColor($color, $asString = false, $separator = ',')
    {
        $color = preg_replace('/[^0-9A-Fa-f]/', '', $color);
        $array = array();

        if (6 === strlen($color)) {
            $color = hexdec($color);

            $array = array(
                0xFF & ($color >> 0x10),
                0xFF & ($color >> 0x8),
                0xFF & $color
            );
        } elseif (3 === strlen($color)) {
            $array = array(
                hexdec(str_repeat(substr($color, 0, 1), 2)),
                hexdec(str_repeat(substr($color, 1, 1), 2)),
                hexdec(str_repeat(substr($color, 2, 1), 2))
            );
        } else {
            throw new \Exception('Color #'.$color.' is not exactly.');
        }

        return $asString ? implode($separator, $array) : $array;
    }
}
