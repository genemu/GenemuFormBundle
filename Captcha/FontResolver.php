<?php
namespace Genemu\Bundle\FormBundle\Captcha;

use Symfony\Component\HttpKernel\Kernel;

class FontResolver
{
    /**
     * @var \Symfony\Component\HttpKernel\Kernel
     */
    protected $kernel;

    /**
     * @var string
     */
    protected $defaultFontsDir;

    /**
     * @param \Symfony\Component\HttpKernel\Kernel $kernel
     * @param $fontsDir
     */
    public function __construct(Kernel $kernel, $fontsDir)
    {
        $this->kernel               = $kernel;
        $this->defaultFontsDir      = $fontsDir;
    }

    /**
     * @param string $font
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function resolve($font)
    {
        if ('@' === $font[0]) {
            return $this->kernel->locateResource($font);
        }

        $pathinfo = pathinfo($font);
        if ('.' === $pathinfo['dirname']) {
            return $this->kernel->locateResource(
                $this->defaultFontsDir . DIRECTORY_SEPARATOR . $pathinfo['basename']
            );
        }

        if (is_file($font) && is_readable($font)) {
            return $font;
        }

        throw new \InvalidArgumentException(sprintf(
            'Could not resolve font "%s".', $font
        ));
    }
}