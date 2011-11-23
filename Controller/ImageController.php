<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Genemu\Bundle\FormBundle\Gd\File\Image;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class ImageController extends Controller
{
    /**
     * @Route("/genemu_change_image", name="genemu_form_image")
     */
    public function changeAction(Request $request)
    {
        $targetPath = $this->container->getParameter('genemu.form.jqueryfile.root_dir');
        $src = $request->get('image');

        $options = $this->container->getParameter('genemu.form.jqueryfile.options');
        $folder = $options['folder'];

        $handle = new Image($targetPath . $this->stripQueryString($src));

        switch ($request->get('filter')) {
            case 'rotate':
                $handle->rotate(90);
                break;
            case 'negative':
                $handle->negate();
                break;
            case 'bw':
                $handle->grayScale();
                break;
            case 'sepia':
                $handle->sepia('#C68039');
                break;
            case 'crop':
                $x = $request->get('x');
                $y = $request->get('y');
                $w = $request->get('w');
                $h = $request->get('h');

                $handle->crop($x, $y, $w, $h);
                break;
            case 'blur':
                $handle->blur();
                break;
            case 'opacity':
                $opacity = $request->get('opacity');

                $handle->opacity($opacity);
            default:
                break;
        }

        $handle->save();
        $thumbnail = $handle;

        if ($this->container->hasParameter('genemu.form.jqueryimage.thumbnails')) {
            $thumbnails = $this->container->getParameter('genemu.form.jqueryimage.thumbnails');

            foreach ($thumbnails as $name => $thumbnail) {
                $handle->createThumbnail($name, $thumbnail[0], $thumbnail[1]);
            }

            $selected = $this->container->getParameter('genemu.form.jqueryimage.selected');
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

    protected function stripQueryString($file)
    {
        if (($pos = strpos($file, '?')) !== false) {
            $file = substr($file, 0, $pos);
        }

        return $file;
    }
}
