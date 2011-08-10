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

/**
 * ReCaptchaTransform
 *
 * @author Olivier Chauvel <olivier@gmail.com>
 */
class ReCaptchaTransform implements DataTransformerInterface
{
    protected $request;
    
    /**
     * Construct
     * 
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        return $value;
    }
    
    /**
     * {@inheritdoc}
     */
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