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

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Base64Controller
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class Base64Controller implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function refreshCaptchaAction()
    {
        $captcha = $this->container->get('genemu.gd.captcha');
        $options = $this->container->get('session')->get('genemu_form.captcha.options', array());
        $captcha->setOptions($options);
        $datas = preg_split('([;,]{1})', substr($captcha->getBase64(), 5));

        return new Response(base64_decode($datas[2]), 200, array('Content-Type' => $datas[0]));
    }

    public function base64Action(Request $request)
    {
        $query = $request->server->get('QUERY_STRING');
        $datas = preg_split('([;,]{1})', $query);

        return new Response(base64_decode($datas[2]), 200, array('Content-Type' => $datas[0]));
    }
}
