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

    public function addBackground($color)
    {
        $color = $this->allocateColor($color);

        imagefill($this->resource, 0, 0, $color);
    }

    public function addBorder($color, $size = 1)
    {
        $color = $this->allocateColor($color);

        imagefilledrectangle($this->resource, 0, 0, $this->width, $size - 1, $color);
        imagefilledrectangle($this->resource, 0, 0, $size - 1, $this->height, $color);
        imagefilledrectangle($this->resource, 0, $this->height, $this->width, $this->height - $size, $color);
        imagefilledrectangle($this->resource, $this->width, 0, $this->width - $size, $this->height, $color);
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
        $this->applyFilters();

        $generate = 'image'.$format;

        ob_start();
        $generate($this->resource);

        return 'data:image/'.$format.';base64,'.base64_encode(ob_get_clean());
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

    public function getResource()
    {
        return $this->resource;
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

        $color = str_replace('#', '', $color);

        if (strlen($color) != 6) {
            throw new \Exception('Color #'.$color.' is not exactly.');
        }

        return imagecolorallocate($this->resource,
            hexdec(substr($color, 0, 2)),
            hexdec(substr($color, 2, 2)),
            hexdec(substr($color, 4, 2))
        );
    }
}
