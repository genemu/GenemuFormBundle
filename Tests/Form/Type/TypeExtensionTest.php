<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olchauvel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Form\Type;

use Genemu\Bundle\FormBundle\Form\Type;
use Symfony\Component\Form\Extension\Core\CoreExtension;

/*
 * TypeExtensionTest
 *
 * @author Olivier Chauvel <olchauvel@gmail.com>
 */
class TypeExtensionTest extends CoreExtension
{
    protected function loadTypes()
    {
        return array_merge(parent::loadTypes(), array(
            new Type\TinymceType('advanced', '/tinymce/tiny_mce.js', array())
        ));
    }
}
