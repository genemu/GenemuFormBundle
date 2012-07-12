<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\JQuery\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormViewInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Select2Type to JQueryLib
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 * @author Chris Tickner <chris.tickner@gmail.com>
 */
class Select2Type extends AbstractType
{
    private $widget;

    public function __construct($widget)
    {
        $this->widget = $widget;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormViewInterface $view, FormInterface $form, array $options)
    {
        $view->setVar('placeholder', $options['placeholder']);
        $view->setVar('allowClear', $options['allowClear']);
        $view->setVar('minimumInputLength', $options['minimumInputLength']);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'placeholder' => '',
            'allowClear'  => 'true',
            'minimumInputLength' => 0,
    ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return $this->widget;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_jqueryselect2';
    }
}
