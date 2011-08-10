<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olchauvel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Form;

use Genemu\Bundle\FormBundle\Form\Type;
use Symfony\Component\Form\Extension\Core\CoreExtension;

/**
 * Represents the main form extension, which loads the core functionality.
 *
 * @author Olivier Chauvel <olchauvel@gmail.com>
 */
class TypeExtensionTest extends CoreExtension
{
    protected function loadTypes()
    {
        $extensions = parent::loadTypes();
        
        return array_merge($extensions, array(
            new Type\TinymceType('advanced', '/tinymce/tiny_mce.js', null, null, null),
            new Type\DoubleListType('double_list', 'double_list_select', 'Associated', 'Unassociated', true),
            new Type\JQueryDateType(false, array())
        ));
    }
}
