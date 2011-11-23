<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\ChoiceList;

use Symfony\Bundle\DoctrineMongoDBBundle\Form\ChoiceList\DocumentChoiceList;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class AjaxDocumentChoiceList extends DocumentChoiceList
{
    /**
     * {@inheritdoc}
     */
    protected function load()
    {
        return array();
    }
}
