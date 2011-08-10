<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olchauvel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Form;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormFactory;

/**
 * abstract TypeTestCase
 *
 * @author Olivier Chauvel <olivier@gmail.com>
 */
abstract class TypeTestCase extends \PHPUnit_Framework_TestCase
{
    protected $factory;
    protected $builder;
    protected $dispatcher;
    protected $typeLoader;

    protected function setUp()
    {
        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->factory = new FormFactory($this->getExtensions());
        $this->builder = new FormBuilder(null, $this->factory, $this->dispatcher);
        
        \Locale::setDefault('de_DE');
    }

    protected function tearDown()
    {
        $this->builder = null;
        $this->dispatcher = null;
        $this->factory = null;
        $this->typeLoader = null;
    }

    protected function getExtensions()
    {
        return array(
            new TypeExtensionTest(),
        );
    }
}