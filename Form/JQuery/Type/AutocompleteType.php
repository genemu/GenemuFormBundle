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

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * @author Bilal Amarni <bilal.amarni@gmail.com>
 */
class AutocompleteType extends AbstractType
{
    private $type;
    private $registry;


    public function __construct($type, ManagerRegistry $registry = null)
    {
        $this->type = $type;
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'configs' => $options['configs'],
            'suggestions' => $options['suggestions'],
            'route_name' => $options['route_name'],
        ));

        // Adds a custom block prefix
        array_splice(
            $view->vars['block_prefixes'],
            array_search($this->getName(), $view->vars['block_prefixes']),
            0,
            'genemu_jqueryautocomplete'
        );
    }

    /**
     * {@inheritdoc}
     *
     * @todo Remove it when bumping requirements to SF 2.7+
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $type = $this->type;
        $registry = $this->registry;

        $resolver->setDefaults(array(
            'configs' => array(),
            'suggestions' => array(),
            'route_name' => null,
            'class' => null,
            'property' => null,
            'em' => null,
            'document_manager' => null,
        ));

        $resolver->setNormalizers(array(
            'em' => function (Options $options, $manager) use ($registry, $type) {
                if (!in_array($type, array('entity', 'document'))) {
                    return null;
                }
                if (null !== $options['document_manager'] && $manager) {
                    throw new \InvalidArgumentException('You cannot set both an "em" and "document_manager" option.');
                }

                $manager = $options['document_manager'] ?: $manager;

                if (null === $manager) {
                    return $registry->getManagerForClass($options['class']);
                }

                return $registry->getManager($manager);
            },
            'suggestions' => function (Options $options, $suggestions) use ($type, $registry) {
                if (null !== $options['route_name']) {
                    return array();
                }
                if (empty($suggestions)) {
                    switch ($type) {
                        case 'entity':
                        case 'document':
                            $propertyPath = $options['property'] ? new PropertyPath($options['property']) : null;
                            $suggestions = array();
                            $objects = $options['em']->getRepository($options['class'])->findAll();
                            foreach ($objects as $object) {
                                if ($propertyPath) {
                                    $suggestions[] = PropertyAccess::createPropertyAccessor()->getValue($object, $propertyPath);
                                } elseif (method_exists($object, '__toString')) {
                                    $suggestions[] = (string) $object;
                                } else {
                                    throw new \RuntimeException(sprintf(
                                        'Cannot cast object of type "%s" to string, ' .
                                        'please implement a __toString method or ' .
                                        'set the "property" option to the desired value.',
                                        get_class($object)
                                    ));
                                }
                            }

                            break;
                    }
                }

                return $suggestions;
            },
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_jqueryautocomplete_' . $this->type;
    }
}
