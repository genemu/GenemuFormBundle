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
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class UploadController extends Controller
{
    /**
     * @Route("/genemu_upload", name="genemu_upload")
     */
    public function uploadAction()
    {
        $request = $this->getRequest();

        $targetPath = $this->container->getParameter('kernel.root_dir').'/../web';
        $handle = $request->files->get('Filedata');
        $folder = $request->get('folder');

        $name = $handle->getClientOriginalName();
        $json = array();

        if ($handle = $handle->move($targetPath.'/'.$folder, $name)) {
            $json = array(
                'result' => 1,
                'file' => $folder.'/'.$name.'?'.time()
            );

            $json['image'] = 0;
            if (preg_match('/image/', $handle->getMimeType())) {
                $size = GetImageSize($targetPath.'/'.$folder.'/'.$name);

                $json['image'] = array(
                    'width' => $size[0],
                    'height' => $size[1]
                );
            }

        } else {
            $json['result'] = 0;
        }

        return new Response(json_encode($json));
    }
}
