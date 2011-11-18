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

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormFactory;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
abstract class TypeTestCase extends \PHPUnit_Framework_TestCase
{
    protected $builder;
    protected $factory;
    protected $dispatcher;

    public function setUp()
    {
        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->factory = new FormFactory($this->getExtensions());
        $this->builder = new FormBuilder(null, $this->factory, $this->dispatcher);

        \Locale::setDefault('de_DE');
    }

    protected function tearDown()
    {
        $this->dispatcher = null;
        $this->factory = null;
        $this->builder = null;
    }

    protected function getExtensions()
    {
        return array(
            new TypeExtensionTest()
        );
    }
}
