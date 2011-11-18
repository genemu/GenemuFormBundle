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

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class JQueryFileTypeTest extends TypeTestCase
{
    public function testDefaultConfigs()
    {
        $form = $this->factory->create('genemu_jqueryfile');
        $view = $form->createView();

        $configs = $view->get('configs');
    }

    public function testConfigs()
    {
    }
}
