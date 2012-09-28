<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Response;

use Genemu\Bundle\FormBundle\Gd\File\Image;

/**
 * Upload Controller
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class UploadController extends ContainerAware
{
    public function uploadAction()
    {
        $handle = $this->container->get('request')->files->get('Filedata');

        $folder = $this->container->getParameter('genemu.form.file.folder');
        $uploadDir = $this->container->getParameter('genemu.form.file.upload_dir');
        $name = uniqid() . '.' . $handle->guessExtension();

        $json = array();
        if ($handle = $handle->move($uploadDir, $name)) {
            $json = array(
                'result' => '1',
                'thumbnail' => array(),
                'image' => array(),
                'file' => ''
            );

            if (preg_match('/image/', $handle->getMimeType())) {
                $handle = new Image($handle->getPathname());
                $thumbnail = $handle;

                if ($this->container->hasParameter('genemu.form.image.thumbnails')) {
                    $thumbnails = $this->container->getParameter('genemu.form.image.thumbnails');

                    foreach ($thumbnails as $name => $thumbnail) {
                        $handle->createThumbnail($name, $thumbnail[0], $thumbnail[1]);
                    }

                    if (0 < count($thumbnails)) {
                        $selected = key(reset($thumbnails));
                        if ($this->container->hasParameter('genemu.form.image.selected')) {
                            $selected = $this->container->getParameter('genemu.form.image.selected');
                        }

                        $thumbnail = $handle->getThumbnail($selected);
                    }
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
