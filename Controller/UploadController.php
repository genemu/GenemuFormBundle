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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Genemu\Bundle\FormBundle\Gd\File\Image;

/**
 * Upload Controller
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class UploadController extends Controller
{
    /**
     * @Route("/genemu_upload", name="genemu_upload")
     */
    public function uploadAction(Request $request)
    {
        $targetPath = $this->container->getParameter('genemu.form.jqueryfile.root_dir');
        $handle = $request->files->get('Filedata');
        $name = uniqid() . '.' . $handle->guessExtension();

        $options = $this->container->getParameter('genemu.form.jqueryfile.options');
        $folder = $options['folder'];

        $json = array();
        if ($handle = $handle->move($targetPath . '/' . $folder, $name)) {
            $json = array(
                'result' => '1',
                'thumbnail' => array(),
                'image' => array(),
                'file' => ''
            );

            if (preg_match('/image/', $handle->getMimeType())) {
                $handle = new Image($handle->getPathname());
                $thumbnail = $handle;

                if ($this->container->hasParameter('genemu.form.jqueryimage.thumbnails')) {
                    $thumbnails = $this->container->getParameter('genemu.form.jqueryimage.thumbnails');

                    foreach ($thumbnails as $name => $thumbnail) {
                        $handle->createThumbnail($name, $thumbnail[0], $thumbnail[1]);
                    }

                    $selected = $this->container->getParameter('genemu.form.jqueryimage.selected');
                    $thumbnail = $handle->getThumbnail($selected);
                }

                $json = array_replace($json, array(
                    'thumbnail' => array(
                        'file' => $folder . '/' . $thumbnail->getFilename() . '?' . time(),
                        'width' => $thumbnail->getWidth(),
                        'height' => $thumbnail->getHeight()
                    ),
                    'image' => array(
                        'width' => $handle->getWidth(),
                        'height' => $handle->getHeight()
                    )
                ));
            }

            $json['file'] = $folder . '/' . $handle->getFilename() . '?' . time();
        } else {
            $json['result'] = '0';
        }

        return new Response(json_encode($json));
    }
}
