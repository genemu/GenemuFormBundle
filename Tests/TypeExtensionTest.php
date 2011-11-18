<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests;

use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpFoundation\SessionStorage\ArraySessionStorage;

use Genemu\Bundle\FormBundle\Form\Type;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class TypeExtensionTest extends CoreExtension
{
    protected function loadTypes()
    {
        return array_merge(parent::loadTypes(), array(
            new Type\TinymceType('advanced', '/js/tinymce/jquery.tinymce.js', array()),
            new Type\JQueryDateType(array()),
            new Type\JQuerySliderType(),
            new Type\CaptchaType(new Session(new ArraySessionStorage()), 's$cr$t', array(
                'script' => 'genemu_upload',
                'uploader' => '/js/uploadify.swf',
                'cancelImg' => '/images/cancel.png',
                'folder' => '/upload',
                'width' => 100,
                'height' => 30,
                'length' => 4,
                'format' => 'png',
                'chars' => '0123456789',
                'font_size' => 18,
                'font_color' => array(
                    '252525',
                    '8B8787',
                    '550707',
                    '3526E6',
                    '88531E'
                ),
                'font_dir' => __DIR__.'/Fixtures/fonts',
                'fonts' => array(
                    'akbar.ttf',
                    'brushcut.ttf',
                    'molten.ttf',
                    'planetbe.ttf',
                    'whoobub.ttf'
                ),
                'background_color' => 'DDDDDD',
                'border_color' => '000000'
            )),
            new Type\JQueryFileType(array(
                'script' => 'genemu_upload',
                'uploader' => '/swf/uploadify.swf',
                'cancel_img' => '/images/cancel.png',
                'folder' => '/upload'
            ), __DIR__.'/Fixtures'),
        ));
    }
}
