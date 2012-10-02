<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Tests\Core\DataTransformer;

use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;
use Genemu\Bundle\FormBundle\Form\Core\DataTransformer\ChoiceToJsonTransformer;

abstract class ChoiceToJsonTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Genemu\Bundle\FormBundle\Exception\WrongUsageOfOption
     */
    public function testReverseMultipleShouldBeFalse()
    {
        $list = new SimpleChoiceList(array(
            'label' => 'Foo', 'value' => 'foo'
        ));
        $transformer = new ChoiceToJsonTransformer(
            $list,
            false,
            'choice',
            true
        );

        $transformer->reverseTransform('{"label": "Foo", "value": "foo"}');
    }

    /**
     * @expectedException Genemu\Bundle\FormBundle\Exception\WrongUsageOfOption
     */
    public function testReverseMultipleShouldBeTrue()
    {
        $list = new SimpleChoiceList(array(
            'label' => 'Foo', 'value' => 'foo'
        ));
        $transformer = new ChoiceToJsonTransformer(
            $list,
            false,
            'choice',
            false
        );

        $transformer->reverseTransform('[{"label": "Foo", "value": "foo"}]');
    }
}
