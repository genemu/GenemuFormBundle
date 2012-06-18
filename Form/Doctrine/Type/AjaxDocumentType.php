<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Doctrine\Type;

use Symfony\Component\Form\AbstractType;
use Doctrine\Common\Persistence\ManagerRegistry;
use Genemu\Bundle\FormBundle\Form\Doctrine\ChoiceList\AjaxEntityChoiceList;
use Symfony\Component\OptionsResolver\Options;

/**
 * AjaxDocumentType
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class AjaxDocumentType extends AbstractType
{
    private $registry;

    /**
     * Constructs
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

        /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        $registry = $this->registry;

        $options = array(
            'document_manager' => null,
            'em' => function (Options $options, $previousValue) use ($registry) {
                if (isset($options['document_manager'])) {
                    return $options['document_manager'];
                }

                return $previousValue;
            },
            'class' => null,
            'property' => null,
            'query_builder' => null,
            'choices' => null,
            'group_by' => null,
            'ajax' => false,
            'choice_list' => function (Options $options, $previousValue) use ($registry) {
                return new AjaxEntityChoiceList(
                    $registry->getManager($options['em']),
                    $options['class'],
                    $options['property'],
                    $options['query_builder'],
                    $options['choices'],
                    $options['group_by'],
                    $options['ajax']
                );
            }
        );

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'document';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_ajaxdocument';
    }
}
