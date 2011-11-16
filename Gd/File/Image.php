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

use Symfony\Component\HttpFoundation\File\File as BaseFile;

use Genemu\Bundle\FormBundle\Gd\Gd;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class Image extends BaseFile
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

    public function getWidth()
    {
        return $this->gd->getWidth();
    }

    public function getHeight()
    {
        return $this->gd->getHeight();
    }
}
