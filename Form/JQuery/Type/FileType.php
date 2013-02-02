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
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Genemu\Bundle\FormBundle\Form\Core\EventListener\FileListener;
use Genemu\Bundle\FormBundle\Form\JQuery\DataTransformer\FileToValueTransformer;

/**
 * FileType
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 * @author Bernhard Schussek <bernhard.schussek@symfony-project.com>
 */
class FileType extends AbstractType
{
    private $options;
    private $rootDir;

    /**
     * Constructs
     *
     * @param array  $options
     * @param string $rootDir
     */
    public function __construct(array $options, $rootDir)
    {
        $this->options = $options;
        $this->rootDir = $rootDir;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $configs = $options['configs'];

        $builder
            ->addEventSubscriber(new FileListener($this->rootDir, $options['multiple']))
            ->addViewTransformer(new FileToValueTransformer($this->rootDir, $configs['folder'], $options['multiple']))
            ->setAttribute('rootDir', $this->rootDir)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'type' => 'hidden',
            'value' => $form->getViewData(),
            'multiple' => $options['multiple'],
            'configs' => $options['configs'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $configs = $this->options;

        $resolver
            ->setDefaults(array(
                'data_class' => null,
                'required' => false,
                'multiple' => false,
                'configs' => array(),
            ))
            ->setNormalizers(array(
                'configs' => function (Options $options, $value) use ($configs) {
                    if (!$options['multiple']) {
                        $value['multi'] = false;
                    }

                    return array_merge($configs, $value);
                }
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
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
