<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Type\JQuery;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

use Genemu\Bundle\FormBundle\Gd\File\Image;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class ImageType extends AbstractType
{
    protected $selected;
    protected $thumbnails;
    protected $filters;

    public function __construct($selected, array $thumbnails, array $filters)
    {
        $this->selected = $selected;
        $this->thumbnails = $thumbnails;
        $this->filters = $filters;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $rootDir = $form->getAttribute('rootDir');
        $configs = $form->getAttribute('configs');
        $folder = $configs['folder'];

        $data = $form->getClientData();

        if ($data) {
            if (!$data instanceof Image) {
                $data = new Image($rootDir . '/' . $data);
            }

            if ($data->hasThumbnail($this->selected)) {
                $thumbnail = $data->getThumbnail($this->selected);

                $view
                    ->set('thumbnail', array(
                        'file' => $folder . '/' . $thumbnail->getFilename(),
                        'width' => $thumbnail->getWidth(),
                        'height' => $thumbnail->getHeight()
                    ));
            }

            $view
                ->set('file', $folder . '/' . $data->getFilename())
                ->set('width', $data->getWidth())
                ->set('height', $data->getHeight());
        }

        $view->set('filters', $this->filters);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'configs' => array(
                'fileExt' => '*.jpg;*.gif;*.png;*.jpeg',
                'fileDesc' => 'Web Image Files (.jpg, .gif, .png, .jpeg)'
            )
        );

        return array_replace_recursive($defaultOptions, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(array $options)
    {
        return 'genemu_jqueryfile';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_jqueryimage';
    }
}
