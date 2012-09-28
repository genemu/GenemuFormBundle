<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Form\Type;

use Symfony\Component\Form\Tests\Extension\Core\Type\TypeTestCase as BaseTypeTestCase;

use Genemu\Bundle\FormBundle\Tests\Form\Extension\TypeExtensionTest;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
abstract class TypeTestCase extends BaseTypeTestCase
{
    public function setUp()
    {
        parent::setUp();

        \Locale::setDefault('en');
    }

    protected function getExtensions()
    {
        return array(
            new TypeExtensionTest($this->createRequestMock())
        );
    }

    protected function createRequestMock()
    {
        return $this->getMock('Symfony\Component\HttpFoundation\Request');
    }
}
