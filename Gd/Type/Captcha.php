<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Gd\Type;

use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

use Genemu\Bundle\FormBundle\Gd\Gd;
use Genemu\Bundle\FormBundle\Gd\Filter\Text;
use Genemu\Bundle\FormBundle\Gd\Filter\Strip;
use Genemu\Bundle\FormBundle\Gd\Filter\Background;
use Genemu\Bundle\FormBundle\Gd\Filter\Border;
use Genemu\Bundle\FormBundle\Gd\Filter\GrayScale;

/**
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class Captcha extends Gd
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var array
     */
    protected $options;

    /**
     * Construct
     *
     * @param string $code
     * @param array $options
     */
    public function __construct($code, array $options)
    {
        $this->code         = $code;
        $this->options      = $options;
    }

    /**
     * @return string
     */
    public function generate()
    {
        return $this->getBase64($this->options['format']);
    }

    /**
     * {@inheritdoc}
     */
    public function getBase64($format = 'png')
    {
        $this->create($this->options['width'], $this->options['height']);

        $this->addFilters(array(
            new Background($this->options['background_color']),
            new Border($this->options['border_color'], $this->options['border_size']),
            new Strip($this->options['font_color'], $this->options['background_stripes_number']),
            new Text(
                $this->code,
                $this->options['font_size'],
                $this->options['fonts'],
                $this->options['font_color'],
                $this->options['font_size_spreading_range'],
                $this->options['chars_rotate_range'],
                $this->options['chars_position_spreading_range'],
                $this->options['chars_spacing']
            ),
        ));
        if ($this->options['grayscale']) {
            $this->addFilter(new GrayScale());
        }

        return parent::getBase64($format);
    }
}
