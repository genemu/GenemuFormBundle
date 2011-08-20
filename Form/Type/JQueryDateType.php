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

/**
 * JQueryDateType
 *
 * @author Olivier Chauvel <olchauvel@gmail.com>
 */
class JQueryDateType extends AbstractType
{
    protected $_options;

    /*
     * Construct
     *
     * @param array $configs
     */
    public function __construct(array $configs)
    {
        $this->_options = array(
            'configs' => $configs
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $configs = array();
        if ($options['widget'] != 'single_text' || isset($options['configs']['buttonImage'])) {
            $configs['showOn'] = 'button';
        }

        $builder
            ->setAttribute('min_year', min($options['years']))
            ->setAttribute('max_year', max($options['years']))
            ->setAttribute('configs', array_replace($configs, $options['configs']));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $view
            ->set('min_year', $form->getAttribute('min_year'))
            ->set('max_year', $form->getAttribute('max_year'))
            ->set('configs', $form->getAttribute('configs'))
            ->set('culture', \Locale::getDefault())
            ->set('javascript_format', $this->getFormatJavascript($form));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        return array_replace($this->_options, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(array $options)
    {
        return 'date';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'genemu_jquerydate';
    }

    /*
     * Create format Date Javascript
     *
     * @param FormInterface $form
     *
     * @return string pattern date of Javascript
     */
    protected function getFormatJavascript(FormInterface $form)
    {
        if ($form->getAttribute('widget') == 'single_text') {
            $pattern = $form->getAttribute('formatter')->getPattern();
            $formats = preg_split('([\\\/.:_;\s-\ ]{1})', $pattern);

            // Transform pattern for JQuery ui datepicker
            $values = array();
            foreach ($formats as $format) {
                switch($format) {
                    case 'yy':
                        $values[$format] = 'y';
                        break;
                    case 'y':
                    case 'yyyy':
                        $values[$format] = 'yy';
                        break;
                    case 'M':
                        $values[$format] = 'm';
                        break;
                    case 'MM':
                    case 'L':
                    case 'LL':
                        $values[$format] = 'mm';
                        break;
                    case 'MMM':
                    case 'LLL':
                        $values[$format] = 'M';
                        break;
                    case 'MMMM':
                    case 'LLLL':
                        $values[$format] = 'MM';
                        break;
                    case 'D':
                        $values[$format] = 'o';
                        break;
                    case 'E':
                    case 'EE':
                    case 'EEE':
                    case 'eee':
                        $values[$format] = 'D';
                        break;
                    case 'EEEE':
                    case 'eeee':
                        $values[$format] = 'DD';
                        break;
                }
            }

            $pattern = str_replace(array_keys($values), array_values($values), $pattern);
        } else {
            $pattern = 'yy-mm-dd';
        }

        return $pattern;
    }
}
