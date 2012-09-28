<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Form\Doctrine\Type;

use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Common\Collections\ArrayCollection;

use Genemu\Bundle\FormBundle\Form\JQuery\Type\AutocompleteType;
use Genemu\Bundle\FormBundle\Tests\Form\Type\TypeTestCase;
use Genemu\Bundle\FormBundle\Tests\Form\Extension\DoctrineOrmExtensionTest;
use Genemu\Bundle\FormBundle\Tests\DoctrineOrmTestCase;
use Genemu\Bundle\FormBundle\Tests\Fixtures\Entity\SingleIdentEntity;

abstract class AbstractAutocompleteTypeTestCase extends TypeTestCase
{
    protected $em;

    protected function tearDown()
    {
        parent::tearDown();

        $this->em = null;
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
        $class = static::SINGLE_IDENT_CLASS;
        $entity1 = new $class(1, 'Foo');
        $entity2 = new $class(2, 'Bar');

        $this->persist(array($entity1, $entity2));

        $form = $this->factory->createNamed('name', $this->getTypeName(), null, array(
            'em' => 'default',
            'class' => static::SINGLE_IDENT_CLASS,
            'property' => 'name',
        ));
        $form->setData(null);

        $view = $form->createView();

        $this->assertEquals(array('Foo', 'Bar'), $view->vars['suggestions']);

        $this->assertNull($form->getData());
        $this->assertEquals('', $form->getViewData());

        $this->assertNull($view->vars['route_name']);
    }

    public function testValueData()
    {
        $class = static::SINGLE_IDENT_CLASS;
        $entity1 = new $class(1, 'Foo');
        $entity2 = new $class(2, 'Bar');

        $this->persist(array($entity1, $entity2));

        $form = $this->factory->createNamed('name', $this->getTypeName(), null, array(
            'em' => 'default',
            'class' => static::SINGLE_IDENT_CLASS,
        ));
        $form->setData('Foo');
        $view = $form->createView();
        $form->bind('Bar');

        $this->assertEquals(array('Foo', 'Bar'), $view->vars['suggestions']);

        $this->assertEquals('Bar', $form->getViewData());
        $this->assertSame('Bar', $form->getData());

        $this->assertNull($view->vars['route_name']);
    }

    public function testValueAjaxData()
    {
        $class = static::SINGLE_IDENT_CLASS;
        $entity1 = new $class(1, 'Foo');
        $entity2 = new $class(2, 'Bar');

        $this->persist(array($entity1, $entity2));

        $form = $this->factory->createNamed('name', $this->getTypeName(), null, array(
            'em' => 'default',
            'class' => static::SINGLE_IDENT_CLASS,
            'property' => 'name',
            'route_name' => 'genemu_ajax'
        ));

        $form->setData('Foo');
        $view = $form->createView();

        $form->bind('Bar');

        $this->assertEquals('genemu_ajax', $view->vars['route_name']);

        $this->assertEquals(array(), $view->vars['suggestions']);
        $this->assertEquals('Bar', $form->getViewData());
        $this->assertSame('Bar', $form->getData());

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
