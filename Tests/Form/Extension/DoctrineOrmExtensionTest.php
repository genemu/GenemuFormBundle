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

use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;

use Genemu\Bundle\FormBundle\Form\Doctrine\Type;
use Genemu\Bundle\FormBundle\Form\JQuery\Type\AutocompleteType;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class DoctrineOrmExtensionTest extends DoctrineOrmExtension
{
    protected function loadTypes()
    {
        return array_merge(parent::loadTypes(), array(
            new Type\AjaxEntityType($this->registry),
            new AutocompleteType('entity', $this->registry),
        ));
    }
}
