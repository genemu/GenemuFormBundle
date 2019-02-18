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

class EntityAutocompleteTypeTest extends AbstractAutocompleteTypeTestCase
{
    const SINGLE_IDENT_CLASS = 'Genemu\Bundle\FormBundle\Tests\Fixtures\Entity\SingleIdentEntity';

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

    protected function getTypeName()
    {
        return 'genemu_jqueryautocomplete_entity';
    }
}
