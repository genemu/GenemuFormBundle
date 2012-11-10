<?php
namespace Genemu\Bundle\FormBundle\Tests\Captcha;

use Genemu\Bundle\FormBundle\Captcha\DefaultFontResolver;

class DefaultFontResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function couldBeConstructedWithRightArguments()
    {
        new DefaultFontResolver($this->createKernelMock(), 'default/font/dir');
    }

    /**
     * @test
     */
    public function shouldResolvFontPathViaKernel()
    {
        $kernel = $this->createKernelMock();
        $kernel
            ->expects($this->once())
            ->method('locateResource')
            ->will($this->returnValue('/full/path/to/resource'))
        ;

        $resolver = new DefaultFontResolver($kernel, 'default/font/dir');
        $result = $resolver->resolve('@BundleName/path/to/resource');

        $this->assertEquals('/full/path/to/resource', $result);
    }

    /**
     * @test
     */
    public function shouldSearchFontsInDefaultFontDir()
    {
        $kernel = $this->createKernelMock();
        $kernel
            ->expects($this->once())
            ->method('locateResource')
            ->with('default/font/dir/resource')
            ->will($this->returnValue('/full/path/to/resource'))
        ;

        $resolver = new DefaultFontResolver($kernel, 'default/font/dir');
        $result = $resolver->resolve('resource');

        $this->assertEquals('/full/path/to/resource', $result);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function throwIfFontPathCouldNotBeResolved()
    {
        $kernel = $this->createKernelMock();
        $kernel
            ->expects($this->never())
            ->method('locateResource')
        ;

        $resolver = new DefaultFontResolver($kernel, 'default/font/dir');
        $resolver->resolve('/unresolved/resource');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\HttpKernel\Kernel
     */
    protected function createKernelMock()
    {
        return $this->getMock('Symfony\\Component\\HttpKernel\\Kernel', array(), array(), '', false);
    }
}