<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olchauvel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\dataTransform;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\Request;

class ReCaptchaTransform implements DataTransformerInterface
{
    protected $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    public function transform($value)
    {
        return $value;
    }
    
    public function reverseTransform($value)
    {
        $request = $this->request->request;
        $server = $this->request->server;
        
        return array(
            'challenge' => $request->get('recaptcha_challenge_field'),
            'response' => $request->get('recaptcha_response_field'),
            'remoteip' => $server->get('REMOTE_ADDR')
        );
    }
}