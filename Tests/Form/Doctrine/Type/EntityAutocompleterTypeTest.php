<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Form\Type\Entity\JQuery;

use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Common\Collections\ArrayCollection;

use Genemu\Bundle\FormBundle\Form\JQuery\Type\AutocompleterType;
use Genemu\Bundle\FormBundle\Tests\Form\Type\TypeTestCase;
use Genemu\Bundle\FormBundle\Tests\Form\Extension\DoctrineOrmExtensionTest;
use Genemu\Bundle\FormBundle\Tests\DoctrineOrmTestCase;
use Genemu\Bundle\FormBundle\Tests\Fixtures\Entity\SingleIdentEntity;

use Symfony\Component\Form\Extension\Core\View\ChoiceView;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class EntityAutocompleterTypeTest extends TypeTestCase
{
    const SINGLE_IDENT_CLASS = 'Genemu\Bundle\FormBundle\Tests\Fixtures\Entity\SingleIdentEntity';

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
            $schemaTool->dropDatabase();
        } catch (\Exception $e) {
        }

        try {
            $schemaTool->createSchema($classes);
        } catch (\Exception $e) {
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

    protected function persist(array $entities)
    {
        foreach ($entities as $entity) {
            $this->em->persist($entity);
        }

        $this->em->flush();
    }

    public function testDefaultValue()
    {
        $entity1 = new SingleIdentEntity(1, 'Foo');
        $entity2 = new SingleIdentEntity(2, 'Bar');

        $this->persist(array($entity1, $entity2));

        $form = $this->factory->createNamed('name', new AutocompleterType('entity'), null, array(
            'em' => 'default',
            'class' => self::SINGLE_IDENT_CLASS,
            'property' => 'name',
        ));
        $form->setData(null);

        $view = $form->createView();

        $this->assertEquals(array(
            1 => new ChoiceView($entity1, 1, 'Foo'),
            2 => new ChoiceView($entity2, 2, 'Bar'),
        ), $view->vars['choices']);

        $this->assertNull($form->getData());
        $this->assertEquals('', $form->getViewData());

        $this->assertNull($view->vars['route_name']);
        $this->assertEquals('', $view->vars['autocompleter_value']);
    }

    public function testMultipleValue()
    {
        $entity1 = new SingleIdentEntity(1, 'Foo');
        $entity2 = new SingleIdentEntity(2, 'Bar');

        $this->persist(array($entity1, $entity2));

        $form = $this->factory->createNamed('name', new AutocompleterType('entity'), null, array(
            'em' => 'default',
            'class' => self::SINGLE_IDENT_CLASS,
            'property' => 'name',

            'multiple' => true
        ));
        $form->setData(null);

        $view = $form->createView();

        $this->assertEquals(array(
                1 => new ChoiceView($entity1, 1, 'Foo'),
                2 => new ChoiceView($entity2, 2, 'Bar'),
            ), $view->vars['choices']);

        $this->assertNull($form->getData());
        $this->assertEquals('', $form->getViewData());

        $this->assertNull($view->vars['route_name']);
        $this->assertEquals('', $view->vars['autocompleter_value']);
    }

    public function testValueData()
    {
        $entity1 = new SingleIdentEntity(1, 'Foo');
        $entity2 = new SingleIdentEntity(2, 'Bar');

        $this->persist(array($entity1, $entity2));

        $form = $this->factory->createNamed('name', new AutocompleterType('entity'), null, array(
            'em' => 'default',
            'class' => self::SINGLE_IDENT_CLASS,
            'property' => 'name',

        ));
        $form->setData($entity1);
        $view = $form->createView();
        $form->bind(json_encode(array(
            'label' => 'Bar',
            'value' => 2
        )));

        $this->assertEquals(array(
            1 => new ChoiceView($entity1, 1, 'Foo'),
            2 => new ChoiceView($entity2, 2, 'Bar'),
        ), $view->vars['choices']);

        $this->assertEquals(json_encode(array(
            'value' => '2',
            'label' => 'Bar'
        )), $form->getViewData());
        $this->assertSame($entity2, $form->getData());

        $this->assertNull($view->vars['route_name']);
        $this->assertEquals('Foo', $view->vars['autocompleter_value']);
    }

    public function testValueMultipleData()
    {
        $entity1 = new SingleIdentEntity(1, 'Foo');
        $entity2 = new SingleIdentEntity(2, 'Bar');

        $this->persist(array($entity1, $entity2));

        $form = $this->factory->createNamed('name', new AutocompleterType('entity'), null, array(
            'em' => 'default',
            'class' => self::SINGLE_IDENT_CLASS,
            'property' => 'name',

            'multiple' => true
        ));
        $existing = new ArrayCollection(array($entity1));

        $form->setData($existing);
        $view = $form->createView();

        $form->bind(json_encode(array(
            array('value' => 1, 'label' => 'Foo'),
            array('value' => 2, 'label' => 'Bar'),
        )));

        $this->assertEquals(array(
            1 => new ChoiceView($entity1, 1, 'Foo'),
            2 => new ChoiceView($entity2, 2, 'Bar'),
        ), $view->vars['choices']);

        $this->assertEquals(json_encode(array(
            array('value' => '1', 'label' => 'Foo'),
            array('value' => '2', 'label' => 'Bar'),
        )), $form->getViewData());
        $this->assertSame($existing, $form->getData());

        $this->assertEquals('Foo, ', $view->vars['autocompleter_value']);
    }

    public function testValueAjaxData()
    {
        $entity1 = new SingleIdentEntity(1, 'Foo');
        $entity2 = new SingleIdentEntity(2, 'Bar');

        $this->persist(array($entity1, $entity2));

        $form = $this->factory->createNamed('name', new AutocompleterType('entity'), null, array(
            'em' => 'default',
            'class' => self::SINGLE_IDENT_CLASS,
            'property' => 'name',
            'route_name' => 'genemu_ajax'
        ));

        $form->setData($entity1);
        $view = $form->createView();

        $form->bind(json_encode(array('value' => 2, 'label' => 'Bar')));

        $this->assertEquals('genemu_ajax', $view->vars['route_name']);

        $this->assertEquals(array(), $view->vars['choices']);
        $this->assertEquals(json_encode(array(
            'value' => 2,
            'label' => 'Bar',
        )), $form->getViewData());
        $this->assertSame($entity2, $form->getData());

        $this->assertEquals('Foo', $view->vars['autocompleter_value']);
    }

    public function testNoValueAjaxMultiple()
    {
        $form = $this->factory->createNamed('name', new AutocompleterType('entity'), null, array(
            'em' => 'default',
            'class' => self::SINGLE_IDENT_CLASS,
            'property' => 'name',
            'route_name' => 'genemu_ajax',
            'multiple' => true
        ));

        $form->bind(null);

        $this->assertTrue($form->getData()->isEmpty());
    }

    public function testValueAjaxMultipleData()
    {
        $entity1 = new SingleIdentEntity(1, 'Foo');
        $entity2 = new SingleIdentEntity(2, 'Bar');

        $this->persist(array($entity1, $entity2));

        $form = $this->factory->createNamed('name', new AutocompleterType('entity'), null, array(
            'em' => 'default',
            'class' => self::SINGLE_IDENT_CLASS,
            'property' => 'name',
            'route_name' => 'genemu_ajax',
            'multiple' => true,
        ));
        $existing = new ArrayCollection(array($entity1, $entity2));

        $form->setData($existing);
        $view = $form->createView();

        $form->bind(json_encode(array(
            array('value' => 2, 'label' => 'Bar')
        )));

        $this->assertEquals('genemu_ajax', $view->vars['route_name']);

        $this->assertEquals(array(), $view->vars['choices']);

        $this->assertEquals(json_encode(array(
            array('value' => 2, 'label' => 'Bar')
        )), $form->getViewData());

        $this->assertSame($existing, $form->getData());
        $this->assertEquals('Foo, Bar, ', $view->vars['autocompleter_value']);
    }

    protected function createRegistryMock($name, $em)
    {
        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $registry->expects($this->any())
            ->method('getManager')
            ->with($this->equalTo($name))
            ->will($this->returnValue($em));

        return $registry;
    }
}
