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

use Genemu\Bundle\FormBundle\Form\EventListener\JQueryFileListener;
use Symfony\Component\HttpFoundation\File\File;

use Genemu\Bundle\FormBundle\Gd\File\Image;

/**
 * JQueryFileType
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 * @author Bernhard Schussek <bernhard.schussek@symfony-project.com>
 */
class JQueryFileType extends AbstractType
{
    protected $options;
    protected $rootDir;

    /**
     * Construct
     *
     * @param array $options
     */
    public function __construct(array $options, $rootDir)
    {
        $this->options = $options;
        $this->rootDir = $rootDir;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $configs = array_merge($this->options, $options['configs']);

        if (
            (isset($options['multiple']) && $options['multiple']) or
            (isset($configs['multi']) && $configs['multi'])
        ) {
            $options['multiple'] = true;
        } else {
            $options['multiple'] = false;
        }

        $builder
            ->addEventSubscriber(new JQueryFileListener($this->rootDir, $options['multiple']))
            ->setAttribute('configs', $configs)
            ->setAttribute('rootDir', $this->rootDir);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $value = $form->getClientData();
        $configs = $form->getAttribute('configs');

        if (!$value instanceof File) {
            $value = new File($this->rootDir . '/' . $value);

            if (preg_match('/image/', $value->getMimeType())) {
                $value = new Image($value->getPathname());
            }
        }

        if ($value instanceof Image) {
            $view
                ->set('width', $value->getWidth())
                ->set('height', $value->getHeight());
        }

        $view
            ->set('value', $configs['folder'] . '/' . $value->getFilename())
            ->set('configs', $form->getAttribute('configs'));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'required' => false,
            'configs' => array()
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
