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

/**
 * Class Base64Controller
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class Base64Controller extends ContainerAware
{
    public function refreshCaptchaAction($name)
    {
        $captcha = $this->container->get('genemu.form.captcha.service')->generateCaptcha($name);

        $data = preg_split('([;,]{1})', substr($captcha->generate(), 5));

        return new Response(base64_decode($data[2]), 200, array('Content-Type' => $data[0]));
    }

    public function base64Action()
    {
        $query = $this->container->get('request')->server->get('QUERY_STRING');
        $datas = preg_split('([;,]{1})', $query);

        return new Response(base64_decode($datas[2]), 200, array('Content-Type' => $datas[0]));
    }
}
