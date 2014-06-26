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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType as BaseDateType;

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
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $configs = $options['configs'];
        $years = $options['years'];

        $configs['dateFormat'] = 'yy-mm-dd';
        if ('single_text' === $options['widget']) {
            $dateFormat = is_int($options['format']) ? $options['format'] : BaseDateType::DEFAULT_FORMAT;
            $timeFormat = \IntlDateFormatter::NONE;
            $calendar   = \IntlDateFormatter::GREGORIAN;
            $pattern    = is_string($options['format']) ? $options['format'] : null;

            $formatter  = new \IntlDateFormatter(
                \Locale::getDefault(),
                $dateFormat,
                $timeFormat,
                'UTC',
                $calendar,
                $pattern
            );
            $formatter->setLenient(false);

            $configs['dateFormat'] = $this->getJavascriptPattern($formatter);
        }

        $view->vars = array_replace($view->vars, array(
            'min_year' => min($years),
            'max_year' => max($years),
            'configs' => $configs,
            'culture' => $options['culture'],
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
                'culture' => \Locale::getPrimaryLanguage(\Locale::getDefault()),
                'widget' => 'choice',
                'years'  => range(date('Y') - 5, date('Y') + 5),
                'configs' => array(
                    'dateFormat' => null,
                ),
            ))
            ->setNormalizers(array(
                'configs' => function (Options $options, $value) use ($configs) {
                    $result = array_merge($configs, $value);
                    if ('single_text' !== $options['widget'] || isset($result['buttonImage'])) {
                        $result['showOn'] = 'button';
                    }

                    return $result;
                }
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
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
