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

require_once __DIR__.'/../../Fixtures/SingleIdentEntity.php';

use Doctrine\ORM\Tools\SchemaTool;

use Genemu\Bundle\FormBundle\Tests\DoctrineOrmExtensionTest;
use Genemu\Bundle\FormBundle\Tests\DoctrineOrmTestCase;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class JQueryAutocompleterTypeTest extends TypeTestCase
{
    const SINGLE_IDENT_CLASS = 'Genemu\Bundle\FormBundle\Tests\Fixtures\SingleIdentEntity';

    const CHOICELIST_CLASS = 'Symfony\Component\Form\Extension\Core\ChoiceList\ArrayChoiceList';

    private $em;

    public function setUp()
    {
        if (!class_exists('Doctrine\\Common\\Version')) {
            $this->markTestSkipped('Doctrine is not available.');
        }

        $this->em = DoctrineOrmTestCase::createTestEntityManager();

        parent::setUp();

        $schemaTool = new SchemaTool($this->em);
        $classes = array(
            $this->em->getClassMetadata(self::SINGLE_IDENT_CLASS)
        );

         try {
            $schemaTool->dropSchema($classes);
        } catch (\Exception $e) {
        }

        try {
            $schemaTool->createSchema($classes);
        } catch(\Exception $e) {
        }
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

    public function testConfigs()
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

        $this->assertEquals(json_encode(array(
            'label' => 'Foo', 'value' => 'foo'
        )), $form->getClientData());

        $this->assertFalse($form->hasAttribute('choice_list'));
        $this->assertEquals(json_encode(array(
            'label' => 'Foo', 'value' => 'foo'
        )), $view->get('value'));

        $this->assertEquals('Foo', $view->get('autocompleter_value'));
    }

    public function testValueMultipleWithAjax()
    {
        $form = $this->factory->create('genemu_jqueryautocompleter', null, array(
            'route_name' => 'genemu_choice',
            'multiple' => true
        ));

        $form->setData(array('foo' => 'Foo', 'bar' => 'Bar'));
        $view = $form->createView();

        $this->assertEquals(json_encode(array(
            array('label' => 'Foo', 'value' => 'foo'),
            array('label' => 'Bar', 'value' => 'bar'),
        )), $form->getClientData());

        $this->assertEquals(array(), $form->getAttribute('choice_list')->getChoices());

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

        $this->assertEquals(json_encode(array(
            array('label' => 'Foo', 'value' => 'foo'),
            array('label' => 'Bar', 'value' => 'bar')
        )), $form->getClientData());

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
        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        /*
        $registry->expects($this->any())
            ->method('getManager')
            ->with($this->equalTo($name))
            ->will($this->returnValue($em));
        */

        return $registry;
    }
}
