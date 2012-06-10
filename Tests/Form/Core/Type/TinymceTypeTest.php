<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Form\Core\Type;

use Genemu\Bundle\FormBundle\Tests\Form\Type\TypeTestCase;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class TinymceTypeTest extends TypeTestCase
{
    public function testDefaultConfigs()
    {
        $form = $this->factory->create('genemu_tinymce');
        $view = $form->createView();

        $options = $view->getVar('options');

        $this->assertEquals(array(
            'language' => 'en'
        ), $view->getVar('configs'));
    }

    public function testConfigs()
    {
        $form = $this->factory->create('genemu_tinymce', null, array(
            'theme' => 'simple',
            'configs' => array(
                'theme_advanced_toolbar_location' => 'top',
                'theme_advanced_toolbar_align' => 'left'
            )
        ));

        $view = $form->createView();

        $configs = $view->getVar('configs');

        $this->assertEquals(array(
            'language' => 'en',
            'theme_advanced_toolbar_location' => 'top',
            'theme_advanced_toolbar_align' => 'left'

        ), $view->getVar('configs'));
    }
}
