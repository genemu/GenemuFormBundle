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
            throw new Exception('Resource does not exists.');
        }
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
