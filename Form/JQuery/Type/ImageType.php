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
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\File;

use Genemu\Bundle\FormBundle\Gd\File\Image;

/**
 * ImageType
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class ImageType extends AbstractType
{
    private $selected;
    private $thumbnails;
    private $filters;

    /**
     * Constructs
     *
     * @param string $selected
     * @param array  $thumbnails
     * @param array  $filters
     */
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
        $configs = $form->getAttribute('configs');
        $data = $form->getClientData();

        if (false === empty($data)) {
            if (false === ($data instanceof Image)) {
                if($data instanceof File) {
                    $data = new Image($form->getAttribute('rootDir') . '/' . $configs['folder']  . '/' . $data->getFilename());
                } else {
                    $data = new Image($form->getAttribute('rootDir') . '/' . $data);
                }
            }

            if (true === $data->hasThumbnail($this->selected)) {
                $thumbnail = $data->getTumbnail($this->selected);

                $view
                    ->set('thumbnail', array(
                        'file' => $configs['folder'] . '/' . $thumbnail->getFilename(),
                        'width' => $thumbnail->getWidth(),
                        'height' => $thumbnail->getHeight(),
                    ));
            }

            if (($configs['custom_storage_folder']) && (false === ($value = $form->getClientData())instanceof File)){
                // This if will be executed only when we load entity with existing file pointed to the folder different
                // from $configs['folder']
            }else{
                $value = $configs['folder'] . '/' . $data->getFilename();
            }

            $view
                ->set('value', $value)
                ->set('file', $value)
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
                'fileExt'  => '*.jpg;*.gif;*.png;*.jpeg',
                'fileDesc' => 'Web Image Files (.jpg, .gif, .png, .jpeg)',
                'auto'     => true,
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