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
            default:
                break;
        }

        $handle->save();

        $json = array(
            'file' => $folder . '/' . $handle->getFilename().'?'.time(),
            'width' => $handle->getWidth(),
            'height' => $handle->getHeight()
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
