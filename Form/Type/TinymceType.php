<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olchauvel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Exception\FormException;

/**
 * TinymceType
 *
 * @author Olivier Chauvel <olchauvel@gmail.com>
 */
class TinymceType extends AbstractType
{
    protected $_options;

    /**
     * Construct
     *
     * @param string $theme
     * @param string $scriptUrl
     * @param array $configs
     */
    public function __construct($theme, $scriptUrl, array $configs)
    {
        $this->_options = array(
            'theme' => $theme,
            'script_url' => $scriptUrl,
            'configs' => $configs
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        if (!$options['script_url']) {
            throw new FormException('The child node "script_url" at path "genenu_form.tinymce" must be configured.');
        }

        $configs = array_merge(array(
            'theme' => $options['theme'],
            'script_url' => $options['script_url'],
            'language' => \Locale::getDefault()
        ), $options['configs']);

        if (empty($configs['mode'])) {
            $configs['mode'] = 'textareas';
        }

        $builder->setAttribute('configs', $configs);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $view->set('configs', $form->getAttribute('configs'));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array_merge(array(
            'required' => false
        ), $this->_options);

        return array_replace($defaultOptions, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(array $options)
    {
        return 'textarea';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_tinymce';
    }
}
