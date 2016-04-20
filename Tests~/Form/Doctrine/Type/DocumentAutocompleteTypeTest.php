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

use Genemu\Bundle\FormBundle\Tests\Form\Type\TypeTestCase;
use Genemu\Bundle\FormBundle\Tests\Form\Extension\DoctrineMongoExtensionTest;
use Genemu\Bundle\FormBundle\Tests\DoctrineMongoTestCase;
use Genemu\Bundle\FormBundle\Form\JQuery\Type\AutocompleterType;

use Genemu\Bundle\FormBundle\Tests\Fixtures\Document\SingleIdentDocument;

class DocumentAutocompleteTypeTest extends AbstractAutocompleteTypeTestCase
{
    protected function getTypeName()
    {
        return 'genemu_jqueryautocomplete_document';
    }

    const SINGLE_IDENT_CLASS = 'Genemu\Bundle\FormBundle\Tests\Fixtures\Document\SingleIdentDocument';

    public function setUp()
    {
        if (!class_exists('Mongo')) {
            $this->markTestSkipped('Mongo PHP/PECL Extension is not available.');
        }

        if (!class_exists('Doctrine\\Common\\Version')) {
            $this->markTestSkipped('Doctrine is not available.');
        }

        $this->em = DoctrineMongoTestCase::createTestDocumentManager();
        $this->em->createQueryBuilder(self::SINGLE_IDENT_CLASS)
            ->remove()
            ->getQuery()
            ->execute();

        parent::setUp();
    }

    protected function getExtensions()
    {
        return array_merge(parent::getExtensions(), array(
            new DoctrineMongoExtensionTest($this->createRegistryMock('default', $this->em)),
        ));
    }
}
