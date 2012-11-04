<?php
namespace Genemu\Bundle\FormBundle\Captcha;

use Symfony\Component\HttpKernel\Kernel;

class DefaultFontResolver implements FontResolverInterface
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
     * {@inherit}
     */
    public function resolve(array $fonts)
    {
        $resolved = array();

        foreach ($fonts as $font) {
            if ('@' === $font[0]) {
                $resolved[] = $this->kernel->locateResource($font);
                continue;
            }

            $pathinfo = pathinfo($font);
            if ('.' === $pathinfo['dirname']) {
                $resolved[] = $this->kernel->locateResource(
                   $this->defaultFontsDir . DIRECTORY_SEPARATOR . $pathinfo['basename']
                );
                continue;
            }

            if (is_file($font) && is_readable($font)) {
                $resolved[] = $font;
                continue;
            }

            throw new \InvalidArgumentException(sprintf(
               'Could not resolve font "%s".', $font
            ));
        }

        return $resolved;
    }
}