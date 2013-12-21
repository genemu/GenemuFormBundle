<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Form\Extension;

use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Request;

use Genemu\Bundle\FormBundle\Gd\Type\Captcha;
use Genemu\Bundle\FormBundle\Form\Core\Validator\ReCaptchaValidator;
use Genemu\Bundle\FormBundle\Form;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class TypeExtensionTest extends CoreExtension
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function loadTypes()
    {
        return array_merge(parent::loadTypes(), array(
            new Form\Core\Type\TinymceType(array()),
            new Form\JQuery\Type\DateType(array()),
            new Form\JQuery\Type\SliderType(),
            new Form\Core\Type\CaptchaType(new Captcha(new Session(new MockArraySessionStorage()), 's$cr$t'), array(
                'script' => 'genemu_upload',
                'uploader' => '/js/uploadify.swf',
                'cancelImg' => '/images/cancel.png',
                'folder' => '/upload',
                'width' => 100,
                'height' => 30,
                'length' => 4,
                'position' => 'left',
                'format' => 'png',
                'chars' => range(0, 9),
                'font_size' => 18,
                'font_color' => array(
                    '252525',
                    '8B8787',
                    '550707',
                    '3526E6',
                    '88531E'
                ),
                'fonts' => array(
                    __DIR__ . '/../../Fixtures/fonts/akbar.ttf',
                    __DIR__ . '/../../Fixtures/fonts/brushcut.ttf',
                    __DIR__ . '/../../Fixtures/fonts/molten.ttf',
                    __DIR__ . '/../../Fixtures/fonts/planetbe.ttf',
                    __DIR__ . '/../../Fixtures/fonts/whoobub.ttf',
                ),
                'background_color' => 'DDDDDD',
                'border_color' => '000000',
                'code' => '1234',
            )),
            new Form\JQuery\Type\FileType(array(
                'script' => 'genemu_upload',
                'uploader' => '/swf/uploadify.swf',
                'cancel_img' => '/images/cancel.png',
                'folder' => '/upload'
            ), __DIR__.'/../../Fixtures'),
            new Form\Core\Type\ReCaptchaType(
                new ReCaptchaValidator(
                    $this->request,
                    'privateKey',
                    array(
                        'host' => 'www.google.com',
                        'port' => 80,
                        'path' => '/recaptcha/api/verify',
                        'timeout' => 10
                    )),
                'publicKey',
                'http://www.google.com/recaptcha/api',
                array()),
            new Form\JQuery\Type\ImageType('medium', array(
                'small' => array(100, 100),
                'medium' => array(200, 200),
                'large' => array(500, 500),
                'extra' => array(1024, 768)
            ), array(
                'rotate',
                'bw',
                'negative',
                'sepia',
                'crop'
            )),
        ));
    }
}
