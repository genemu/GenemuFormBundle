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
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class TinymceTypeTest extends TypeTestCase
{
    public function testDefaultConfigs()
    {
        $form = $this->factory->create('genemu_tinymce');
        $view = $form->createView();

        $options = $view->get('options');

        $this->assertEquals('de_DE', $options['language']);
        $this->assertEquals('advanced', $options['theme']);
        $this->assertEquals('/js/tinymce/jquery.tinymce.js', $options['script_url']);
    }

    public function testConfigs()
    {
        $form = $this->factory->create('genemu_tinymce', null, array(
            'theme' => 'simple',
            'options' => array(
                'theme_advanced_toolbar_location' => 'top',
                'theme_advanced_toolbar_align' => 'left'
            )
        ));

        $view = $form->createView();

        $options = $view->get('options');

        $this->assertEquals('de_DE', $options['language']);
        $this->assertEquals('simple', $options['theme']);
        $this->assertEquals('/js/tinymce/jquery.tinymce.js', $options['script_url']);
        $this->assertEquals('top', $options['theme_advanced_toolbar_location']);
        $this->assertEquals('left', $options['theme_advanced_toolbar_align']);
    }
}
