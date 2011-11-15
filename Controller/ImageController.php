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

use Symfony\Component\HttpFoundation\File\File;

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

        $handle = new File($targetPath . $this->stripQueryString($src));

        $source = imagecreatefromjpeg($handle->getPathname());
        $rotate = imagerotate($source, 90, 0);

        imagejpeg($rotate, $handle->getPathname(), 100);

        $json = array('file' => $this->stripQueryString($src).'?'.time());

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
