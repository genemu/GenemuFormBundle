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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormViewInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\File;

use Genemu\Bundle\FormBundle\Form\Core\EventListener\FileListener;

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
        $configs = array_replace($this->options, $options['configs']);
        if (isset($configs['multi']) && $configs['multi']) {
            $options['multiple'] = true;
        }

        if ($options['multiple']) {
            $configs['multi'] = true;
        }

        $builder
            ->addEventSubscriber(new FileListener($this->rootDir, $options['multiple']))
            ->setAttribute('configs', $configs)
            ->setAttribute('rootDir', $this->rootDir)
            ->setAttribute('multiple', $options['multiple']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormViewInterface $view, FormInterface $form, array $options)
    {
        $configs = $form->getAttribute('configs');
        $datas = $form->getClientData();

        if (!empty($datas)) {
            if ($form->getAttribute('multiple')) {
                $datas = is_scalar($datas) ? explode(',', $datas) : $datas;
                $value = array();

                foreach ($datas as $data) {
                    if (!$data instanceof File) {
                        $data = new File($form->getAttribute('rootDir') . '/' . $data);
                    }

                    $value[] = $configs['folder'] . '/' . $data->getFilename();
                }

                $value = implode(',', $value);
            } else {
                if (!$datas instanceof File) {
                    $datas = new File($form->getAttribute('rootDir') . '/' . $datas);
                }

                $value = $configs['folder'] . '/' . $datas->getFilename();
            }

            $view->setVar('value', $value);
        }

        $view
            ->setVar('type', 'hidden')
            ->setVar('configs', $form->getAttribute('configs'));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        return array(
            'required' => false,
            'multiple' => false,
            'configs' => array(),
        );
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
