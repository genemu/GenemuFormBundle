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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
        $handle = $request->files->get('Filedata');
        if(substr($handle->getClientOriginalName(),strrpos($handle->getClientOriginalName(),".")+1) == "php") {
            throw new AccessDeniedException();
        }

        $folder = $this->container->getParameter('genemu.form.file.folder');
        $uploadDir = $this->container->getParameter('genemu.form.file.upload_dir');
        if($this->container->hasParameter('genemu.form.file.disable_guess_extension')) {
            $name = uniqid() . '.' . substr($handle->getClientOriginalName(),strrpos($handle->getClientOriginalName(),".")+1);
        } else {
            $name = uniqid() . '.' . $handle->guessExtension();
        }

        $json = array();
        if (false !== ($handle = $handle->move($uploadDir, $name))) {
            $json = array(
                'result' => '1',
                'thumbnail' => array(),
                'image' => array(),
                'file' => ''
            );

            if (0 === strpos($handle->getMimeType(), 'image')) {
                $handle = new Image($handle->getPathname());
                $thumbnail = $handle;

                if (true === $this->container->hasParameter('genemu.form.image.thumbnails')) {
                    $thumbnails = $this->container->getParameter('genemu.form.image.thumbnails');

                    foreach ($thumbnails as $name => $thumbnail) {
                        $handle->createThumbnail($name, $thumbnail[0], $thumbnail[1]);
                    }

                    $selected = key(reset($thumbnails));
                    if (true === $this->container->hasParameter('genemu.form.image.selected')) {
                        $selected = $this->container->getParameter('genemu.form.image.selected');
                    }

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
