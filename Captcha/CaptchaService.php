<?php
namespace Genemu\Bundle\FormBundle\Captcha;

use Symfony\Component\HttpFoundation\Session\Session;
use Genemu\Bundle\FormBundle\Gd\Type\Captcha;

class CaptchaService
{
    /**
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    protected $session;

    /**
     * @var CodeEncoderInterface
     */
    protected $encoder;

    /**
     * @var CodeGeneratorInterface
     */
    protected $generator;

    /**
     * @var FontResolverInterface
     */
    protected $fontResolver;

    /**
     * @var string
     */
    protected $sessionCodeKey = 'genemu_form.captcha.code';

    /**
     * @var string
     */
    protected $sessionOptionsKey = 'genemu_form.captcha.options';

    /**
     * @param CodeEncoderInterface $encoder
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     */
    public function __construct(
        CodeEncoderInterface $encoder,
        CodeGeneratorInterface $generator,
        FontResolverInterface $fontResolver,
        Session $session
    ) {
        $this->encoder      = $encoder;
        $this->generator    = $generator;
        $this->fontResolver = $fontResolver;
        $this->session      = $session;
    }

    /**
     * @param $options
     *
     * @return \Genemu\Bundle\FormBundle\Gd\Type\Captcha
     */
    public function createCaptcha($options)
    {
        $options['fonts'] = $this->fontResolver->resolve($options['fonts']);
        $this->setOptions($options);

        return $this->generateCaptcha($options);
    }

    /**
     * @return \Genemu\Bundle\FormBundle\Gd\Type\Captcha
     *
     * @throws \LogicException
     */
    public function createCaptchaWithLastOptions()
    {
        $options = $this->getOptions();
        if(false == $options) {
            throw new \LogicException('Captcha should be created with options first');
        }

        return $this->generateCaptcha($options);
    }

    /**
     * Is code valid
     *
     * @param $code
     *
     * @return bool
     */
    public function isCodeValid($code)
    {
        return $this->encoder->encode($code) === $this->getCode();
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->session->get($this->sessionCodeKey);
    }

    /**
     * Remove code
     */
    public function removeCode()
    {
        $this->session->remove($this->sessionCodeKey);
    }

    protected function generateCaptcha(array $options)
    {
        $code = $this->generator->generate($options['chars'], $options['length']);
        $this->setCode($code);

        return new Captcha($code, $options);
    }

    /**
     * @param array $options
     */
    protected function setOptions(array $options)
    {
        $this->session->set($this->sessionOptionsKey, $options);
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return $this->session->get($this->sessionOptionsKey, array());
    }

    /**
     * Set code
     *
     * @param string
     */
    protected function setCode($code)
    {
        $this->session->set($this->sessionCodeKey, $this->encoder->encode($code));
    }
}