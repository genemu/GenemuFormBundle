<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ArrayChoiceList;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

use Genemu\Bundle\FormBundle\Form\DataTransformer\FieldToJsonTransformer;
use Genemu\Bundle\FormBundle\Form\DataTransformer\ArrayToJsonTransformer;
use Genemu\Bundle\FormBundle\Form\DataTransformer\ChoiceToJsonTransformer;
use Genemu\Bundle\FormBundle\Form\DataTransformer\EntityIdsToJsonTransformer;
use Genemu\Bundle\FormBundle\Form\DataTransformer\EntityIdToJsonTransformer;

use Genemu\Bundle\FormBundle\Form\ChoiceList\AjaxChoiceList;

/**
 * JQueryAutocompleterType
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class JQueryAutocompleterType extends AbstractType
{
    protected $registry;

    /**
     * Construct
     *
     * @param mixed $registry
     */
    public function __construct($registry)
    {
        if (!$registry instanceof RegistryInterface && !$registry instanceof ManagerRegistry) {
            throw new \InvalidArgumentException('__construct accept a RegistryInterface or ManagerRegistry. Registry is:'.get_class($registry));
        }

        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        if (!$options['route_name'] && 'entity' !== $options['widget']) {
            $choiceList = null;

            if (isset($options['choices']) && $options['choices']) {
                $choiceList = $options['choices'];
            }

            if (isset($options['choice_list']) && $options['choice_list']) {
                $choiceList = $options['choice_list']->getChoices();
            }

            if ($choiceList) {
                $choices = array();
                foreach ($choiceList as $value => $label) {
                    $choices[] = array(
                        'label' => $label,
                        'value' => $value
                    );
                }

                $options['choice_list'] = new ArrayChoiceList($choices);
                $builder->setAttribute('choice_list', $options['choice_list']);
            }
        }

        if (isset($options['multiple']) && $options['multiple']) {
            $transformer = null;

            switch ($options['widget']) {
                case 'entity':
                    $transformer = new EntityIdsToJsonTransformer($options['choice_list'], $options['route_name']);
                    break;
                case 'choice':
                    if (isset($options['choice_list']) && $options['choice_list']) {
                        $transformer = new ChoiceToJsonTransformer($options['choice_list']);
                    } else {
                        $transformer = new ArrayToJsonTransformer();
                    }
                default:
                    break;
            }

            if ($transformer) {
                $builder->appendClientTransformer($transformer);
            }

            $builder->setAttribute('multiple', true);
        } else {
            if (!isset($options['choice_list']) && $options['route_name']) {
                $builder->appendClientTransformer(new FieldToJsonTransformer());
            } elseif ('entity' === $options['widget']) {
                $builder->appendClientTransformer(new EntityIdToJsonTransformer($options['choice_list'], $options['route_name']));
            }

            $builder->setAttribute('multiple', false);
        }

        $builder
            ->setAttribute('widget', $options['widget'])
            ->setAttribute('route_name', $options['route_name']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $data = $form->getClientData();

        if (
            $form->getAttribute('multiple') ||
            $form->getAttribute('route_name') ||
            'entity' === $form->getAttribute('widget')
        ) {
            $data = json_decode($data, true);
        }

        $value = '';

        if ($form->getAttribute('multiple') && $data) {
            foreach ($data as $val) {
                $value .= $val['label'].', ';
            }
        } else {
            if ($form->hasAttribute('choice_list') && $form->getAttribute('choice_list')) {
                $choices = $form->getAttribute('choice_list')->getChoices();

                if ('entity' === $form->getAttribute('widget')) {
                    $value = $data['label'];
                } else {
                    foreach ($choices as $choice) {
                        if ($choice['value'] === $data) {
                            $value = $choice['label'];
                            break;
                        }
                    }
                }
            } elseif ($form->getAttribute('route_name')) {
                $value = $data['label'];
            } else {
                $value = $data;
            }
        }

        if (!$value) {
            $view->set('value', '');
        }

        $view
            ->set('autocompleter_value', $value)
            ->set('route_name', $form->getAttribute('route_name'));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'widget' => 'choice',
            'route_name' => null
        );

        if (isset($options['widget']) && 'entity' === $options['widget']) {
            $defaultOptions = array_merge($defaultOptions, array(
                'em' => null,
                'class' => null,
                'property' => null,
                'query_builder' => null,
                'choices' => array(),
                'group_by' => null
            ));
        }

        $options = array_replace($defaultOptions, $options);

        if (
            'choice' === $options['widget'] &&
            $options['route_name'] &&
            !isset($options['choices']) &&
            (
                !isset($options['multiple']) ||
                isset($options['multiple']) && !$options['multiple']
            )
        ) {
            $options['widget'] = 'field';
        }

        if ('entity' === $options['widget'] && $options['route_name']) {
            $method = $this->registry instanceof RegistryInterface ? 'getEntityManager' : 'getManager';

            $options['choice_list'] = new AjaxChoiceList(
                $this->registry->$method($options['em']),
                $options['class'],
                $options['property'],
                $options['query_builder'],
                $options['choices'],
                $options['group_by']
            );
        }

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(array $options)
    {
        return $options['widget'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_jqueryautocompleter';
    }
}
