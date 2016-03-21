<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Form\Type\Document\JQuery;

use Doctrine\Common\Collections\ArrayCollection;

use Genemu\Bundle\FormBundle\Tests\Form\Type\TypeTestCase;
use Genemu\Bundle\FormBundle\Tests\Form\Extension\DoctrineMongoExtensionTest;
use Genemu\Bundle\FormBundle\Tests\DoctrineMongoTestCase;
use Genemu\Bundle\FormBundle\Form\JQuery\Type\AutocompleterType;

use Genemu\Bundle\FormBundle\Tests\Fixtures\Document\SingleIdentDocument;

use Symfony\Component\Form\Extension\Core\View\ChoiceView;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class DocumentAutocompleterTypeTest extends TypeTestCase
{
    const SINGLE_IDENT_CLASS = 'Genemu\Bundle\FormBundle\Tests\Fixtures\Document\SingleIdentDocument';

    private $documentManager;

    public function setUp()
    {
        if (!class_exists('Mongo')) {
            $this->markTestSkipped('Mongo PHP/PECL Extension is not available.');
        }

        if (!class_exists('Doctrine\\Common\\Version')) {
            $this->markTestSkipped('Doctrine is not available.');
        }

        $this->documentManager = DoctrineMongoTestCase::createTestDocumentManager();
        $this->documentManager->createQueryBuilder(self::SINGLE_IDENT_CLASS)
            ->remove()
            ->getQuery()
            ->execute();

        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->documentManager = null;
    }

    protected function getExtensions()
    {
        return array_merge(parent::getExtensions(), array(
            new DoctrineMongoExtensionTest($this->createRegistryMock('default', $this->documentManager)),
        ));
    }

    protected function persist(array $documents)
    {
        foreach ($documents as $document) {
            $this->documentManager->persist($document);
        }

        $this->documentManager->flush();
    }

    public function testDefaultValue()
    {
        $document1 = new SingleIdentDocument('azerty1', 'Foo');
        $document2 = new SingleIdentDocument('azerty2', 'Bar');

        $this->persist(array($document1, $document2));

        $form = $this->factory->createNamed('name', new AutocompleterType('document'), null, array(
            'document_manager' => 'default',
            'class' => self::SINGLE_IDENT_CLASS,
            'property' => 'name',
        ));
        $form->setData(null);

        $view = $form->createView();

        $this->assertEquals(array(
            new ChoiceView($document1, 'azerty1', 'Foo'),
            new ChoiceView($document2, 'azerty2', 'Bar'),
        ), $view->vars['choices']);

        $this->assertNull($form->getData());
        $this->assertEquals('', $form->getViewData());

        $this->assertNull($view->vars['route_name']);
        $this->assertEquals('', $view->vars['autocompleter_value']);
    }

    public function testMultipleValue()
    {
        $document1 = new SingleIdentDocument(1, 'Foo');
        $document2 = new SingleIdentDocument(2, 'Bar');

        $this->persist(array($document1, $document2));

        $form = $this->factory->createNamed('name', new AutocompleterType('document'), null, array(
            'document_manager' => 'default',
            'class' => self::SINGLE_IDENT_CLASS,
            'property' => 'name',
            'multiple' => true
        ));
        $form->setData(null);

        $view = $form->createView();

        $this->assertEquals(array(
            new ChoiceView($document1, 1, 'Foo'),
            new ChoiceView($document2, 2, 'Bar'),
        ), $view->vars['choices']);

        $this->assertNull($form->getData());
        $this->assertEquals('', $form->getViewData());

        $this->assertNull($view->vars['route_name']);
        $this->assertEquals('', $view->vars['autocompleter_value']);
    }

    public function testValueData()
    {
        $document1 = new SingleIdentDocument('azerty1', 'Foo');
        $document2 = new SingleIdentDocument(2, 'Bar');

        $this->persist(array($document1, $document2));

        $form = $this->factory->createNamed('name', new AutocompleterType('document'), null, array(
            'document_manager' => 'default',
            'class' => self::SINGLE_IDENT_CLASS,
            'property' => 'name',
        ));
        $form->setData($document1);
        $view = $form->createView();
        $form->bind(json_encode(array(
            'label' => 'Bar',
            'value' => 2
        )));

        $this->assertEquals(array(
            new ChoiceView($document1, 'azerty1', 'Foo'),
            new ChoiceView($document2, 2, 'Bar'),
        ), $view->vars['choices']);

        $this->assertEquals(json_encode(array(
            'value' => '2',
            'label' => 'Bar'
        )), $form->getViewData());
        $this->assertSame($document2, $form->getData());

        $this->assertNull($view->vars['route_name']);
        $this->assertEquals('Foo', $view->vars['autocompleter_value']);
    }

    public function testValueMultipleData()
    {
        $document1 = new SingleIdentDocument(1, 'Foo');
        $document2 = new SingleIdentDocument(2, 'Bar');

        $this->persist(array($document1, $document2));

        $form = $this->factory->createNamed('name', new AutocompleterType('document'), null, array(
            'document_manager' => 'default',
            'class' => self::SINGLE_IDENT_CLASS,
            'property' => 'name',
            'multiple' => true
        ));
        $existing = new ArrayCollection(array($document1));

        $form->setData($existing);
        $view = $form->createView();

        $form->bind(json_encode(array(
            array('value' => 1, 'label' => 'Foo'),
            array('value' => 2, 'label' => 'Bar'),
        )));

        $this->assertEquals(array(
            new ChoiceView($document1, 1, 'Foo'),
            new ChoiceView($document2, 2, 'Bar'),
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
        $document1 = new SingleIdentDocument(1, 'Foo');
        $document2 = new SingleIdentDocument(2, 'Bar');

        $this->persist(array($document1, $document2));

        $form = $this->factory->createNamed('name', new AutocompleterType('document'), null, array(
            'document_manager' => 'default',
            'class' => self::SINGLE_IDENT_CLASS,
            'property' => 'name',
            'route_name' => 'genemu_ajax'
        ));

        $form->setData($document1);
        $view = $form->createView();

        $form->bind(json_encode(array('value' => 2, 'label' => 'Bar')));

        $this->assertEquals('genemu_ajax', $view->vars['route_name']);

        $this->assertEquals(array(), $view->vars['choices']);
        $this->assertEquals(json_encode(array(
            'value' => 2,
            'label' => 'Bar',
        )), $form->getViewData());
        $this->assertSame($document2, $form->getData());

        $this->assertEquals('Foo', $view->vars['autocompleter_value']);
    }

    public function testValueAjaxMultipleData()
    {
        $document1 = new SingleIdentDocument(1, 'Foo');
        $document2 = new SingleIdentDocument(2, 'Bar');

        $this->persist(array($document1, $document2));

        $form = $this->factory->createNamed('name', new AutocompleterType('document'), null, array(
            'document_manager' => 'default',
            'class' => self::SINGLE_IDENT_CLASS,
            'property' => 'name',
            'route_name' => 'genemu_ajax',
            'multiple' => true,
        ));
        $existing = new ArrayCollection(array($document1, $document2));

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

    protected function createRegistryMock($name, $dm)
    {
        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $registry->expects($this->any())
            ->method('getManager')
            ->with($this->equalTo($name))
            ->will($this->returnValue($dm));

        return $registry;
    }
}
