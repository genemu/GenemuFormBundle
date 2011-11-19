<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\From\Type\Entity;

use Doctrine\ORM\Tools\SchemaTool;

use Genemu\Bundle\FormBundle\Tests\Form\Type\TypeTestCase;
use Genemu\Bundle\FormBundle\Tests\Form\Extension\DoctrineOrmExtensionTest;
use Genemu\Bundle\FormBundle\Tests\DoctrineOrmTestCase;

use Genemu\Bundle\FormBundle\Tests\Fixtures\SingleIdentEntity;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class JQueryAutocompleterTypeTest extends TypeTestCase
{
    const SINGLE_IDENT_CLASS = 'Genemu\Bundle\FormBundle\Tests\Fixtures\SingleIdentEntity';

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
            var_dump($e->getMessage());
            exit();
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

    public function testEntityValue()
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

        $view = $form->createView();

        $this->assertEquals(array(
            array('label' => 'Foo', 'value' => 1),
            array('label' => 'Bar', 'value' => 2),
        ), $form->getAttribute('choice_list')->getChoices());
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
