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

    protected $width;
    protected $height;

    public function __construct($resource)
    {
        $this->setResource($resource);
    }

    public function checkFormat($format)
    {
        if (!function_exists('image'.$format)) {
            throw new \Exception('Format '.$format.' does not support.');
        }
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getBase64($format = 'png')
    {
        $format = 'jpg' === $format ? 'jpeg' : $format;
        $generate = 'image'.$format;

        $this->checkFormat($format);
        $this->applyFilters();

        ob_start();
        $generate($this->resource);

        return 'data:image/'.$format.';base64,'.base64_encode(ob_get_clean());
    }

    public function save($file, $format = 'png', $quality = 100)
    {
        $format = 'jpg' === $format ? 'jpeg' : $format;
        $generate = 'image'.$format;

        $this->checkFormat($format);
        $this->applyFilters();

        $generate($this->resource, $file, $quality);
    }

    public function addFilter(Filter $filter)
    {
        $this->filters[] = $filter;
    }

    public function addFilters(array $filters)
    {
        foreach ($filters as $filter) {
            $this->addFilter($filter);
        }
    }

    public function applyFilters()
    {
        foreach ($this->filters as $filter) {
            $filter->setResource($this->resource);

            $this->setResource($filter->apply());
        }
    }

    public function create($width, $height)
    {
        $this->setResource(imagecreatetruecolor($width, $height));
    }

    public function reset()
    {
        $this->create($this->width, $this->height);
    }

    public function setResource($resource)
    {
        if (!is_resource($resource)) {
            throw new \Exception('Resource does not exists.');
        }

        $this->resource = $resource;
        $this->width = imagesx($resource);
        $this->height = imagesy($resource);
    }

    public function allocateColors(array $colors)
    {
        $array = array();
        foreach ($colors as $color) {
            $array[] = $this->allocateColor($color);
        }

        return $array;
    }

    public function allocateColor($color)
    {
        if (!is_resource($this->resource)) {
            throw new \Exception('Resource does not exists.');
        }

        list($red, $green, $blue) = $this->hexColor($color);

        return imagecolorallocate($this->resource, $red, $green, $blue);
    }

    public function hexColor($color)
    {
        $color = str_replace('#', '', $color);

        if (strlen($color) != 6) {
            throw new \Exception('Color #'.$color.' is not exactly.');
        }

        return array(
            hexdec(substr($color, 0, 2)),
            hexdec(substr($color, 2, 2)),
            hexdec(substr($color, 4, 2))
        );
    }
}
