<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\JQuery\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * RatingType
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 * @author Tom Adam <tomadam@instantiate.co.uk>
 */
class RatingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAttribute('configs', $options['configs']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['configs'] = $form->getConfig()->getAttribute('configs');
        if (!isset($view->vars['configs']['required'])) {
            $view->vars['configs']['required'] = $options['required'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'number' => 5,
            'configs' => array(),
            'expanded' => true,
            'choices' => function (Options $options) {
                $choices = array();
                for ($i=1; $i<=$options['number']; $i++) {
                    $choices[$i] = null;
                }
                return $choices;
            }
        ));

        $resolver->setNormalizers(array(
            'expanded' => function (Options $options, $value) {
                return true;
            }
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_jqueryrating';
    }
}
