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

/**
 * Class Base64Controller
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class Base64Controller extends Controller
{
    /**
     * @Route("/genemu_base64", name="genemu_base64")
     */
    public function base64Action(Request $request)
    {
        $query = $request->server->get('QUERY_STRING');
        $datas = preg_split('([;,]{1})', $query);

        return new Response(base64_decode($datas[2]), 200, array('Content-Type' => $datas[0]));
    }
}
