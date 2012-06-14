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

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * DateType
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class DateType extends AbstractType
{
    private $options;

    /**
     * Constructs
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
        $options = $this->getDefaultOptions($options);

        $builder
            ->setAttribute('years', $options['years'])
            ->setAttribute('culture', $options['culture'])
            ->setAttribute('configs', $options['configs']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $configs = $form->getAttribute('configs');
        $year = $form->getAttribute('years');

        if (false === isset($configs['dateFormat']) && true === empty($configs['dateFormat'])) {
            $configs['dateFormat'] = 'yy-mm-dd';
            if ('single_text' === $form->getAttribute('widget')) {
                $formatter = $form->getAttribute('formatter');

                $configs['dateFormat'] = $this->getJavascriptPattern($formatter);
            }
        }
        
        $culture = str_replace('_', '-', $form->getAttribute('culture'));

        $view
            ->set('min_year', min($year))
            ->set('max_year', max($year))
            ->set('configs', $configs)
            ->set('culture', $culture);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'culture' => \Locale::getDefault(),
            'widget' => 'choice',
            'configs' => array_merge(array(
                'dateFormat' => null,
            ), $this->options),
        );

        $options = array_replace_recursive($defaultOptions, $options);

        if ('single_text' !== $options['widget'] || true === isset($options['configs']['buttonImage'])) {
            $options['configs']['showOn'] = 'button';
        }

        return $options;
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

    /**
     * Create pattern Date Javascript
     *
     * @param IntlDateFormatter $formatter
     *
     * @return string pattern date of Javascript
     */
    protected function getJavascriptPattern(\IntlDateFormatter $formatter)
    {
        $pattern = $formatter->getPattern();
        $patterns = preg_split('([\\\/.:_;,\s-\ ]{1})', $pattern);
        $exits = array();

        // Transform pattern for JQuery ui datepicker
        foreach ($patterns as $index => $val) {
            switch ($val) {
                case 'yy':
                    $exits[$val] = 'y';
                    break;
                case 'y':
                case 'yyyy':
                    $exits[$val] = 'yy';
                    break;
                case 'M':
                    $exits[$val] = 'm';
                    break;
                case 'MM':
                case 'L':
                case 'LL':
                    $exits[$val] = 'mm';
                    break;
                case 'MMM':
                case 'LLL':
                    $exits[$val] = 'M';
                    break;
                case 'MMMM':
                case 'LLLL':
                    $exits[$val] = 'MM';
                    break;
                case 'D':
                    $exits[$val] = 'o';
                    break;
                case 'E':
                case 'EE':
                case 'EEE':
                case 'eee':
                    $exits[$val] = 'D';
                    break;
                case 'EEEE':
                case 'eeee':
                    $exits[$val] = 'DD';
                    break;
            }
        }

        return str_replace(array_keys($exits), array_values($exits), $pattern);
    }
}
