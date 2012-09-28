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
            return 'jpeg';
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
     * @param int    $quality
     */
    public function createThumbnail($name, $path, $width, $height, $format = 'png', $quality = 90)
    {
        $ratio = ($this->width > $width || $this->height > $height)
            ?($width > $height
                ?$width / $height
                :$height / $width
            ):1;

        $width_tmp = $this->width * $ratio;
        $height_tmp = $this->height * $ratio;

        if ($height_tmp > $height) {
            $height_tmp = $height;
            $width_tmp = ($height / $this->height) * $this->width;
        }

        if ($width_tmp > $width) {
            $width_tmp = $width;
            $height_tmp = ($width / $this->width) * $this->height;
        }

        $tmp = imagecreatetruecolor($width_tmp, $height_tmp);

        imagecopyresampled($tmp, $this->resource, 0, 0, 0, 0, $width_tmp, $height_tmp, $this->width, $this->height);

        $format = $this->checkFormat($format);
        $generate = 'image'.$format;

        if ('jpeg' === $format) {
            $generate($tmp, $path, $quality);
        } else {
            $generate($tmp, $path);
        }

        imagedestroy($tmp);

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

        if ('jpeg' === $format) {
            $generate($this->resource, $path, $quality);
        } else {
            $generate($this->resource, $path);
        }
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
     * Get color
     *
     * @param int $x
     * @param int $y
     *
     * @return array
     */
    public function getColor($x, $y)
    {
        $this->checkResource();

        if ($x < 0 || $x > ($this->width - 1) || $y < 0 || $y > ($this->height - 1)) {
            return 0xFFFFFF;
        }

        return imagecolorat($this->resource, $x, $y);
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
    public function allocateColor($color, $alpha = null)
    {
        $this->checkResource();

        list($red, $green, $blue) = $this->hexColor($color);

        if ($alpha) {
            return imagecolorallocatealpha($this->resource, $red, $green, $blue, 255 * $alpha);
        } else {
            return imagecolorallocate($this->resource, $red, $green, $blue);

        }
    }

    /**
     * {@inheritdoc}
     */
    public function hexColor($color, $asString = false, $separator = ',')
    {
        $color = preg_replace('/[^0-9A-Fa-f]/', '', $color);

        if (3 === strlen($color)) {
            $color = preg_replace ('/(?(?=[^0-9a-f])[^.]|(.))/i', '$1$1', $color);
        }

        if (6 !== strlen($color)) {
            throw new \Exception(sprintf('Color #%s is not exactly.', $color));
        }

        $color = hexdec($color);
        $array = array(
            0xFF & ($color >> 0x10),
            0xFF & ($color >> 0x8),
            0xFF & $color
        );

        return $asString ? implode($separator, $array) : $array;
    }

    public function intColor($int)
    {
        $hex = imagecolorsforindex($this->resource, $int);

        return array_values($hex);
    }

    public function bilinearInterpolate($x, $y, $nw, $ne, $sw, $se)
    {
        list($r0, $g0, $b0) = $this->intColor($nw);
        list($r1, $g1, $b1) = $this->intColor($ne);
        list($r2, $g2, $b2) = $this->intColor($sw);
        list($r3, $g3, $b3) = $this->intColor($se);

        $cx = 1.0 - $x;
        $cy = 1.0 - $y;

        $m0 = $cx * $r0 + $x * $r1;
        $m1 = $cx * $r2 + $x * $r3;
        $r = (int) ($cy * $m0 + $y * $m1);

        $m0 = $cx * $g0 + $x * $g1;
        $m1 = $cx * $g2 + $x * $g3;
        $g = (int) ($cy * $m0 + $y * $m1);

        $m0 = $cx * $b0 + $x * $b1;
        $m1 = $cx * $b2 + $x * $b3;
        $b = (int) ($cy * $m0 + $y * $m1);

        return ($r << 16) | ($g << 8) | $b;
    }
}
