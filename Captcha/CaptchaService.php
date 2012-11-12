<?php
namespace Genemu\Bundle\FormBundle\Captcha;

use Genemu\Bundle\FormBundle\Gd\Type\Captcha;

class CaptchaService
{
    /**
     * @var CaptchaStorage
     */
    protected $captchaStorage;

    /**
     * @var CodeGenerator
     */
    protected $codeGenerator;

    /**
     * @var FontResolver
     */
    protected $fontResolver;

    /**
     * @var array
     */
    protected $options;

    /**
     * @param CodeGenerator $codeGenerator
     * @param FontResolver $fontResolver
     * @param CaptchaStorage $captchaStorage
     * @param array $options
     */
    public function __construct(
        CodeGenerator $codeGenerator,
        FontResolver $fontResolver,
        CaptchaStorage $captchaStorage,
        array $options
    ) {
        $this->codeGenerator    = $codeGenerator;
        $this->fontResolver     = $fontResolver;
        $this->captchaStorage   = $captchaStorage;
        $this->options          = $options;
    }

    /**
     * @param string $name
     *
     * @return \Genemu\Bundle\FormBundle\Gd\Type\Captcha
     */
    public function generateCaptcha($name)
    {
        $options = $this->getConfig($name);

        $options['fonts'] = $this->resolveFonts($options['fonts']);

        $code = $this->codeGenerator->generate($options['chars'], $options['length']);

        $this->captchaStorage->setCode($name, $code);

        return new Captcha($code, $options);
    }

    /**
     * Is code valid
     *
     * @param $name
     * @param $code
     *
     * @return bool
     */
    public function isCodeValid($name, $code)
    {
        return $code ? $code === $this->captchaStorage->getCode($name) : false;
    }

    /**
     * @param string $name
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    protected function getConfig($name)
    {
        if (isset($this->options[$name])) {
            return $this->options[$name];
        }

        throw new \InvalidArgumentException(sprintf(
            'Could not find captcha config "%s"', $name
        ));
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
}