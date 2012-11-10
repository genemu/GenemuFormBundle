<?php
namespace Genemu\Bundle\FormBundle\Captcha;

use Genemu\Bundle\FormBundle\Gd\Type\Captcha;

class CaptchaService
{
    /**
     * @var CaptchaStorageInterface
     */
    protected $captchaStorage;

    /**
     * @var CodeEncoderInterface
     */
    protected $codeEncoder;

    /**
     * @var CodeGeneratorInterface
     */
    protected $codeGenerator;

    /**
     * @var FontResolverInterface
     */
    protected $fontResolver;

    /**
     * @param CodeEncoderInterface $codeEncoder
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     */
    public function __construct(
        CodeEncoderInterface $codeEncoder,
        CodeGeneratorInterface $codeGenerator,
        FontResolverInterface $fontResolver,
        CaptchaStorageInterface $captchaStorage
    ) {
        $this->codeEncoder      = $codeEncoder;
        $this->codeGenerator    = $codeGenerator;
        $this->fontResolver     = $fontResolver;
        $this->captchaStorage   = $captchaStorage;
    }

    /**
     * @param $options
     *
     * @return \Genemu\Bundle\FormBundle\Gd\Type\Captcha
     */
    public function generateCaptcha(array $options)
    {
        $options['fonts'] = $this->resolveFonts($options['fonts']);

        return $this->createCaptcha($options);
    }

    /**
     * @return \Genemu\Bundle\FormBundle\Gd\Type\Captcha
     *
     * @throws \LogicException
     */
    public function createCaptchaWithLastOptions()
    {
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
        return $this->codeEncoder->encode($code) === $this->captchaStorage->getCode();
    }

    /**
     * @param array $fonts
     *
     * @return array
     */
    protected function resolveFonts(array $fonts)
    {
        $resolvedFonts = array();

        foreach ($fonts as $font) {
            $resolvedFonts[] = $this->fontResolver->resolve($font);
        }

        return $resolvedFonts;
    }

    protected function createCaptcha(array $options)
    {
        $code = $this->codeGenerator->generate($options['chars'], $options['length']);
        $this->captchaStorage->setCode($code);

        return new Captcha($code, $options);
    }
}