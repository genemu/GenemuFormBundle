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

use Genemu\Bundle\FormBundle\Tests\Form\Extension\DoctrineOrmExtensionTest;
use Genemu\Bundle\FormBundle\Tests\DoctrineOrmTestCase;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class JQueryAutocompleterTypeTest extends TypeTestCase
{
    const CHOICELIST_CLASS = 'Symfony\Component\Form\Extension\Core\ChoiceList\ArrayChoiceList';

    private $em;

    public function setUp()
    {
        if (!class_exists('Doctrine\\Common\\Version')) {
            $this->markTestSkipped('Doctrine is not available.');
        }

        $this->em = DoctrineOrmTestCase::createTestEntityManager();

        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em = null;
    }

    protected function getExtensions()
    {
        return array_merge(parent::getExtensions(), array(
            new DoctrineOrmExtensionTest($this->createRegistryMock('default', $this->em)),
        ));
    }

    public function testDefaultConfigs()
    {
        $form = $this->factory->create('genemu_jqueryautocompleter');
        $view = $form->createView();

        $this->assertInstanceOf(self::CHOICELIST_CLASS, $form->getAttribute('choice_list'));
        $this->assertEquals(array(), $form->getAttribute('choice_list')->getChoices());
        $this->assertEquals('', $form->getClientData());
        $this->assertEquals('', $view->get('value'));
        $this->assertEquals('', $view->get('autocompleter_value'));
        $this->assertNull($view->get('route_name'));
    }

    public function testValue()
    {
        $form = $this->factory->create('genemu_jqueryautocompleter', null, array(
            'choices' => array('foo' => 'Foo', 'bar' => 'Bar')
        ));

        $form->setData('foo');
        $view = $form->createView();

        $this->assertTrue($form->hasAttribute('choice_list'));
        $this->assertEquals(array(
            array('label' => 'Foo', 'value' => 'foo'),
            array('label' => 'Bar', 'value' => 'bar')
        ), $form->getAttribute('choice_list')->getChoices());

        $this->assertTrue($form->hasAttribute('multiple'));
        $this->assertFalse($form->getAttribute('multiple'));

        $this->assertEquals('foo', $form->getClientData());
        $this->assertEquals('foo', $view->get('value'));
        $this->assertEquals('Foo', $view->get('autocompleter_value'));
    }

    public function testValueWithAjax()
    {
        $form = $this->factory->create('genemu_jqueryautocompleter', null, array(
            'route_name' => 'genemu_choice'
        ));

        $form->setData(array('foo' => 'Foo'));
        $view = $form->createView();
        $form->bind(json_encode(array('bar' => 'Bar')));

        $this->assertFalse($form->hasAttribute('choice_list'));
        $this->assertEquals(json_encode(array(
            'label' => 'Foo', 'value' => 'foo'
        )), $view->get('value'));

        $this->assertEquals('Foo', $view->get('autocompleter_value'));

        $this->assertEquals(array('bar' => 'Bar'), $form->getData());
        $this->assertEquals(json_encode(array(
            'label' => 'Bar', 'value' => 'bar'
        )), $form->getClientData());

    }

    public function testValueMultipleWithAjax()
    {
        $form = $this->factory->create('genemu_jqueryautocompleter', null, array(
            'route_name' => 'genemu_choice',
            'multiple' => true
        ));

        $form->setData(array('foo' => 'Foo', 'bar' => 'Bar'));
        $view = $form->createView();
        $form->bind(json_encode(array('foo' => 'Foo', 'ri' => 'Ri')));

        $this->assertEquals(array(), $form->getAttribute('choice_list')->getChoices());
        $this->assertEquals(json_encode(array(
            array('label' => 'Foo', 'value' => 'foo'),
            array('label' => 'Ri', 'value' => 'ri'),
        )), $form->getClientData());
        $this->assertEquals(array('foo' => 'Foo', 'ri' => 'Ri'), $form->getData());

        $this->assertEquals(json_encode(array(
            array('label' => 'Foo', 'value' => 'foo'),
            array('label' => 'Bar', 'value' => 'bar'),
        )), $view->get('value'));
        $this->assertEquals('Foo, Bar, ', $view->get('autocompleter_value'));
    }

    public function testValueMultiple()
    {
        $form = $this->factory->create('genemu_jqueryautocompleter', null, array(
            'choices' => array('foo' => 'Foo', 'bar' => 'Bar', 'ri' => 'Ri'),
            'multiple' => true
        ));

        $form->setData(array('foo', 'bar'));
        $view = $form->createView();
        $form->bind(json_encode(array('foo')));

        $this->assertEquals(json_encode(array(
            array('label' => 'Foo', 'value' => 'foo'),
        )), $form->getClientData());

        $this->assertEquals(array('foo'), $form->getData());

        $this->assertEquals(array(
            array('label' => 'Foo', 'value' => 'foo'),
            array('label' => 'Bar', 'value' => 'bar'),
            array('label' => 'Ri', 'value' => 'ri'),
        ), $form->getAttribute('choice_list')->getChoices());

        $this->assertEquals(json_encode(array(
            array('label' => 'Foo', 'value' => 'foo'),
            array('label' => 'Bar', 'value' => 'bar')
        )), $view->get('value'));

        $this->assertEquals('Foo, Bar, ', $view->get('autocompleter_value'));
    }

    protected function createRegistryMock($name, $em)
    {
        if (isset($_SERVER['SYMFONY_VERSION']) && $_SERVER['SYMFONY_VERSION'] === 'origin/master') {
            $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        } else {
            $registry = $this->getMock('Symfony\Bridge\Doctrine\RegistryInterface');
        }

        return $registry;
    }
}
