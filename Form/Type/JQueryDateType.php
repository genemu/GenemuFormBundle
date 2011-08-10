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

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class JQueryDateType extends AbstractType
{
    private $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->setAttribute('min_year', min($options['years']))
            ->setAttribute('max_year', max($options['years']))
            ->setAttribute('image', $options['image'])
            ->setAttribute('config', ($options['config'])?$options['config']:'{}');
    }
    
    public function buildView(FormView $view, FormInterface $form)
    {
        if($form->getAttribute('widget') == 'single_text') {
             $pattern = $form->getAttribute('formatter')->getPattern();
             
        } else {
            $pattern = 'yy-mm-dd';
        }
        
        $view
            ->set('min_year', $form->getAttribute('min_year'))
            ->set('max_year', $form->getAttribute('max_year'))
            ->set('image', $form->getAttribute('image'))
            ->set('config', $form->getAttribute('config'))
            ->set('culture', \Locale::getDefault())
            ->set('javascript_format', $pattern);
    }
    
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'image' => $this->container->getParameter('genemu.form.jquerydate.image'),
            'config' => $this->container->getParameter('genemu.form.jquerydate.config')
        );

        return array_replace($defaultOptions, $options);
    }
    
    public function getParent(array $options)
    {
        return 'date';
    }
    
    public function getName()
    {
        return 'genemu_jquerydate';
    }
}