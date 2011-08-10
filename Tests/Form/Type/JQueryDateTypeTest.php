<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olchauvel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Form\Type;

use Genemu\Bundle\FormBundle\Tests\Form\TypeTestCase;

/**
 * JQueryDatetTypeTest
 *
 * @author Olivier Chauvel <olivier@gmail.com>
 */
class JQueryDatetTypeTest extends TypeTestCase
{
    protected function setup()
    {
        parent::setup();
        if (!extension_loaded('intl')) {
            $this->markTestSkipped('The "intl" extension is not available');
        }
    }
    
    public function testMinYear()
    {
        $form = $this->factory->create('genemu_jquerydate', null, array(
            'data_timezone' => 'UTC',
            'user_timezone' => 'UTC',
            'years' => array(2010, 2011),
            'input' => 'string'
        ));
        $form->bind('2.6.2010');
        $view = $form->createView();
        
        $this->assertEquals(2000, $view->get('min_year'));
    }
}