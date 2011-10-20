<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Form\Type;

/**
 * TinymceTest
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class TinymceTypeTest extends TypeTestCase
{
    public function testDefaultConfigs()
    {
        $form = $this->factory->create('genemu_tinymce');
        $view = $form->createView();

        $configs = $view->get('configs');

        $this->assertEquals('de_DE', $configs['language']);
        $this->assertEquals('advanced', $configs['theme']);
        $this->assertEquals('/tinymce/tiny_mce.js', $configs['script_url']);
        $this->assertEquals('textareas', $configs['mode']);
    }

    public function testConfigs()
    {
        $form = $this->factory->create('genemu_tinymce', null, array(
            'configs' => array(
                'mode' => 'exact',
                'theme' => 'simple',
                'script_url' => '/js/tinymce/tiny_mce.js',
                'theme_advanced_toolbar_location' => 'top',
                'theme_advanced_toolbar_align' => 'left'
            )
        ));
        $view = $form->createView();

        $configs = $view->get('configs');

        $this->assertEquals('exact', $configs['mode']);
        $this->assertEquals('simple', $configs['theme']);
        $this->assertEquals('/js/tinymce/tiny_mce.js', $configs['script_url']);
        $this->assertEquals('top', $configs['theme_advanced_toolbar_location']);
        $this->assertEquals('left', $configs['theme_advanced_toolbar_align']);
    }
}
