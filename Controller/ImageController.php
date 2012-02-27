<?php

/**
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Genemu\Bundle\FormBundle\Gd\File\Image;

/**
 * Class ImageController
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class ImageController extends Controller
{
    /**
     * @Route("/genemu_change_image", name="genemu_form_image")
     */
    public function changeAction(Request $request)
    {
        $rootDir = rtrim($this->container->getParameter('genemu.form.file.root_dir'), '/\\') . DIRECTORY_SEPARATOR;
        $folder = rtrim($this->container->getParameter('genemu.form.file.folder'), '/\\') . DIRECTORY_SEPARATOR;

        $file = $request->get('image');
        $handle = new Image($rootDir . $this->stripQueryString($file));

        switch ($request->get('filter')) {
            case 'rotate':
                $handle->addFilterRotate(90);
                break;
            case 'negative':
                $handle->addFilterNegative();
                break;
            case 'bw':
                $handle->addFilterBw();
                break;
            case 'sepia':
                $handle->addFilterSepia('#C68039');
                break;
            case 'crop':
                $x = $request->get('x');
                $y = $request->get('y');
                $w = $request->get('w');
                $h = $request->get('h');

                $handle->addFilterCrop($x, $y, $w, $h);
                break;
            case 'blur':
                $handle->addFilterBlur();
            default:
                break;
        }

        $handle->save();
        $thumbnail = $handle;

        if ($this->container->hasParameter('genemu.form.image.thumbnails')) {
            $thumbnails = $this->container->getParameter('genemu.form.image.thumbnails');

            foreach ($thumbnails as $name => $thumbnail) {
                $handle->createThumbnail($name, $thumbnail[0], $thumbnail[1]);
            }

            $selected = key(reset($thumbnails));
            if ($this->container->hasParameter('genemu.form.image.selected')) {
                $selected = $this->container->getParameter('genemu.form.image.selected');
            }

            $thumbnail = $handle->getThumbnail($selected);
        }

        $json = array(
            'result' => '1',
            'file' => $folder . '/' . $handle->getFilename() . '?' . time(),
            'thumbnail' => array(
                'file' => $folder . '/' . $thumbnail->getFilename() . '?' . time(),
                'width' => $thumbnail->getWidth(),
                'height' => $thumbnail->getHeight()
            ),
            'image' => array(
                'width' => $handle->getWidth(),
                'height' => $handle->getHeight()
            )
        );

        return new Response(json_encode($json));
    }

    /**
     * Delete info after `?`
     *
     * @param string $file
     *
     * @return string
     */
    private function stripQueryString($file)
    {
        if (false !== ($pos = strpos($file, '?'))) {
            $file = substr($file, 0, $pos);
        }

        return $file;
    }
}
