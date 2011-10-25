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

/**
 * JQueryFileType
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class JQueryFileType extends AbstractType
{
    protected $options;

    /**
     * Construct
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $configs = $options['configs'];

        if (isset($options['multiple']) && $options['multiple']) {
            $configs['multi'] = true;
        }

        $builder
            ->setAttribute('configs', $configs);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $view
            ->set('configs', $form->getAttribute('configs'));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'required' => false,
            'configs' => $this->options
        );

        return array_replace($defaultOptions, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(array $options)
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_jqueryfile';
    }
}
