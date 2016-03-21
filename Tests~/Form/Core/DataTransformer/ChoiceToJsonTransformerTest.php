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

class ChoiceToJsonTransformerTest extends \PHPUnit_Framework_TestCase
{
    public function testReverseEmptySimpleValue()
    {
        $transformer = $this->createChoiceToJsonTransformer(
            array(),
            false
        );

        $this->assertEquals(NULL, $transformer->reverseTransform(''));
    }

    public function testReverseEmptyArrayValue()
    {
        $transformer = $this->createChoiceToJsonTransformer(
            array(),
            true
        );

        $this->assertEquals(array(), $transformer->reverseTransform('[]'));
    }

    /**
     * @expectedException Symfony\Component\Form\Exception\TransformationFailedException
     */
    public function testReverseMultipleShouldBeFalse()
    {
        $transformer = $this->createChoiceToJsonTransformer(
            array('label' => 'Foo', 'value' => 'foo'),
            true
        );

        $transformer->reverseTransform('{"label": "Foo", "value": "foo"}');
    }

    /**
     * @expectedException Symfony\Component\Form\Exception\TransformationFailedException
     */
    public function testReverseMultipleShouldBeTrue()
    {
        $transformer = $this->createChoiceToJsonTransformer(
            array('label' => 'Foo', 'value' => 'foo'),
            false
        );

        $transformer->reverseTransform('[{"label": "Foo", "value": "foo"}]');
    }

    protected function createChoiceToJsonTransformer($list, $multiple)
    {
        $simpleChoice = new SimpleChoiceList($list);

        $transformer = new ChoiceToJsonTransformer(
            $simpleChoice,
            false,
            'choice',
            $multiple
        );

        return $transformer;
    }
}
