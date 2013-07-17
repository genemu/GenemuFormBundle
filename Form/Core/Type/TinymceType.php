<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Core\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Templating\Helper\CoreAssetsHelper;

/**
 * TinymceType
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class TinymceType extends AbstractType
{
    private $assetsHelper;
    private $options;

    /**
     * Constructs
     *
     * @param array $options
     */
    public function __construct(CoreAssetsHelper $assetsHelper, array $options)
    {
        $this->assetsHelper = $assetsHelper;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['configs'] = $options['configs'];

        if (isset($view->vars['configs']['script_url'])) {
            $view->vars['configs']['script_url'] = $this->assetsHelper->getUrl($view->vars['configs']['script_url']);
        } else {
            $view->vars['configs']['mode'] = 'exact';
            $view->vars['configs']['elements'] = $view->vars['id'];
        }

        if (isset($view->vars['configs']['content_css']) && is_array($view->vars['configs']['content_css'])) {
            foreach ($view->vars['configs']['content_css'] as $index => $path) {
                $view->vars['configs']['content_css'][$index] = $this->assetsHelper->getUrl($path);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $configs = array_merge($this->options, array(
            'language' => \Locale::getDefault(),
        ));

        $resolver
            ->setDefaults(array(
                'configs' => array(),
                'required' => false,
                'theme' => 'default',
            ))
            ->setAllowedTypes(array(
                'configs' => 'array',
                'theme' => 'string',
            ))
            ->setNormalizers(array(
                'configs' => function (Options $options, $value) use ($configs) {
                    return array_merge($configs, $value);
                },
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
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
