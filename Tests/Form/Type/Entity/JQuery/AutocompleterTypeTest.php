<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\From\Type\Entity\JQuery;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Common\Collections\ArrayCollection;

use Genemu\Bundle\FormBundle\Tests\Form\Type\TypeTestCase;
use Genemu\Bundle\FormBundle\Tests\Form\Extension\DoctrineOrmExtensionTest;
use Genemu\Bundle\FormBundle\Tests\DoctrineOrmTestCase;

use Genemu\Bundle\FormBundle\Tests\Fixtures\Entity\SingleIdentEntity;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class AutocompleterTypeTest extends TypeTestCase
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

        $form = $this->factory->createNamed('genemu_jqueryautocompleter', 'name', null, array(
            'em' => 'default',
            'class' => self::SINGLE_IDENT_CLASS,
            'property' => 'name',
            'widget' => 'entity'
        ));
        $form->setData(null);

        $view = $form->createView();

        $this->assertEquals(array(
            array('value' => 1, 'label' => 'Foo'),
            array('value' => 2, 'label' => 'Bar'),
        ), $form->getAttribute('choice_list')->getChoices());

        $this->assertNull($form->getData());
        $this->assertEquals('', $form->getClientData());

        $this->assertNull($view->get('route_name'));
        $this->assertEquals('', $view->get('autocompleter_value'));
    }

    public function testMultipleValue()
    {
        $entity1 = new SingleIdentEntity(1, 'Foo');
        $entity2 = new SingleIdentEntity(2, 'Bar');

        $this->persist(array($entity1, $entity2));

        $form = $this->factory->createNamed('genemu_jqueryautocompleter', 'name', null, array(
            'em' => 'default',
            'class' => self::SINGLE_IDENT_CLASS,
            'property' => 'name',
            'widget' => 'entity',
            'multiple' => true
        ));
        $form->setData(null);

        $view = $form->createView();

        $this->assertEquals(array(
            array('value' => 1, 'label' => 'Foo'),
            array('value' => 2, 'label' => 'Bar'),
        ), $form->getAttribute('choice_list')->getChoices());

        $this->assertNull($form->getData());
        $this->assertEquals('', $form->getClientData());

        $this->assertNull($view->get('route_name'));
        $this->assertEquals('', $view->get('autocompleter_value'));
    }

    public function testValueData()
    {
        $entity1 = new SingleIdentEntity(1, 'Foo');
        $entity2 = new SingleIdentEntity(2, 'Bar');

        $this->persist(array($entity1, $entity2));

        $form = $this->factory->createNamed('genemu_jqueryautocompleter', 'name', null, array(
            'em' => 'default',
            'class' => self::SINGLE_IDENT_CLASS,
            'property' => 'name',
            'widget' => 'entity',
        ));
        $form->setData($entity1);
        $view = $form->createView();
        $form->bind(json_encode(array(
            'label' => 'Bar',
            'value' => 2
        )));

        $this->assertEquals(array(
            array('value' => 1, 'label' => 'Foo'),
            array('value' => 2, 'label' => 'Bar'),
        ), $form->getAttribute('choice_list')->getChoices());

        $this->assertEquals(json_encode(array(
            'value' => 2,
            'label' => 'Bar'
        )), $form->getClientData());
        $this->assertSame($entity2, $form->getData());

        $this->assertNull($view->get('route_name'));
        $this->assertEquals('Foo', $view->get('autocompleter_value'));
    }

    public function testValueMultipleData()
    {
        $entity1 = new SingleIdentEntity(1, 'Foo');
        $entity2 = new SingleIdentEntity(2, 'Bar');

        $this->persist(array($entity1, $entity2));

        $form = $this->factory->createNamed('genemu_jqueryautocompleter', 'name', null, array(
            'em' => 'default',
            'class' => self::SINGLE_IDENT_CLASS,
            'property' => 'name',
            'widget' => 'entity',
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
            array('value' => 1, 'label' => 'Foo'),
            array('value' => 2, 'label' => 'Bar'),
        ), $form->getAttribute('choice_list')->getChoices());

        $this->assertEquals(json_encode(array(
            array('value' => 1, 'label' => 'Foo'),
            array('value' => 2, 'label' => 'Bar'),
        )), $form->getClientData());
        $this->assertSame($existing, $form->getData());

        $this->assertEquals('Foo, ', $view->get('autocompleter_value'));
    }

    public function testValueAjaxData()
    {
        $entity1 = new SingleIdentEntity(1, 'Foo');
        $entity2 = new SingleIdentEntity(2, 'Bar');

        $this->persist(array($entity1, $entity2));

        $form = $this->factory->createNamed('genemu_jqueryautocompleter', 'name', null, array(
            'em' => 'default',
            'class' => self::SINGLE_IDENT_CLASS,
            'property' => 'name',
            'widget' => 'entity',
            'route_name' => 'genemu_ajax'
        ));

        $form->setData($entity1);
        $view = $form->createView();

        $form->bind(json_encode(array('value' => 2, 'label' => 'Bar')));

        $this->assertEquals('genemu_ajax', $view->get('route_name'));

        $this->assertEquals(array(), $form->getAttribute('choice_list')->getChoices());
        $this->assertEquals(json_encode(array(
            'value' => 2,
            'label' => 'Bar',
        )), $form->getClientData());
        $this->assertSame($entity2, $form->getData());

        $this->assertEquals('Foo', $view->get('autocompleter_value'));
    }

    public function testValueAjaxMultipleData()
    {
        $entity1 = new SingleIdentEntity(1, 'Foo');
        $entity2 = new SingleIdentEntity(2, 'Bar');

        $this->persist(array($entity1, $entity2));

        $form = $this->factory->createNamed('genemu_jqueryautocompleter', 'name', null, array(
            'em' => 'default',
            'class' => self::SINGLE_IDENT_CLASS,
            'property' => 'name',
            'widget' => 'entity',
            'route_name' => 'genemu_ajax',
            'multiple' => true,
        ));
        $existing = new ArrayCollection(array($entity1, $entity2));

        $form->setData($existing);
        $view = $form->createView();

        $form->bind(json_encode(array(
            array('value' => 2, 'label' => 'Bar')
        )));

        $this->assertEquals('genemu_ajax', $view->get('route_name'));

        $this->assertEquals(array(), $form->getAttribute('choice_list')->getChoices());

        $this->assertEquals(json_encode(array(
            array('value' => 2, 'label' => 'Bar')
        )), $form->getClientData());

        $this->assertSame($existing, $form->getData());
        $this->assertEquals('Foo, Bar, ', $view->get('autocompleter_value'));
    }

    protected function createRegistryMock($name, $em)
    {
        if (isset($_SERVER['SYMFONY_VERSION']) && $_SERVER['SYMFONY_VERSION'] === 'origin/master') {
            $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
            $registry->expects($this->any())
                ->method('getManager')
                ->with($this->equalTo($name))
                ->will($this->returnValue($em));
        } else {
            $registry = $this->getMock('Symfony\Bridge\Doctrine\RegistryInterface');
            $registry->expects($this->any())
                ->method('getEntityManager')
                ->with($this->equalTo($name))
                ->will($this->returnValue($em));
        }

        return $registry;
    }
}
