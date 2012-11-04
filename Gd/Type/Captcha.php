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
     * {@inheritdoc}
     */
    public function getBase64($format = 'png')
    {
        $this->create($this->options['width'], $this->options['height']);

        $this->addFilters(array(
            new Background($this->options['background_color']),
            new Border($this->options['border_color']),
            new Strip($this->options['font_color']),
            new Text($this->code, $this->options['font_size'], $this->options['fonts'], $this->options['font_color']),
        ));
        if ($this->options['grayscale']) {
            $this->addFilter(new GrayScale());
        }

        return parent::getBase64($format);
    }
}
