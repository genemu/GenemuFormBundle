<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Form\Extension;

use Doctrine\Bundle\MongoDBBundle\Form\DoctrineMongoDBExtension;

use Genemu\Bundle\FormBundle\Form\Doctrine\Type;
use Genemu\Bundle\FormBundle\Form\JQuery\Type\AutocompleteType;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class DoctrineMongoExtensionTest extends DoctrineMongoDBExtension
{
    protected function loadTypes()
    {
        return array_merge(parent::loadTypes(), array(
            new Type\AjaxDocumentType($this->registry),
            new AutocompleteType('document', $this->registry),
        ));
    }
}
