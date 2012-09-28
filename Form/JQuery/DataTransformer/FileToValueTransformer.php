<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\JQuery\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * {@inheritdoc}
 *
 * @author Bilal Amarni <bilal.amarni@gmail.com>
 */
class FileToValueTransformer implements DataTransformerInterface
{
    protected $rootDir;
    protected $multiple;
    protected $folder;

    /**
     * Constructs
     *
     * @param string  $rootDir
     * @param boolean $multiple
     */
    public function __construct($rootDir, $folder, $multiple = false)
    {
        $this->rootDir = $rootDir;
        $this->multiple = $multiple;
        $this->folder = $folder;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($datas)
    {
        if (empty($datas)) {
            return '';
        }

        if ($this->multiple) {
            $datas = is_scalar($datas) ? explode(',', $datas) : $datas;
            $value = array();

            foreach ($datas as $data) {
                if (!$data instanceof File) {
                    $data = new File($this->rootDir . '/' . $this->stripQueryString($data));
                }

                $value[] = $this->folder . '/' . $data->getFilename();
            }

            $value = implode(',', $value);
        } else {
            if (!$datas instanceof File) {
                $datas = new File($this->rootDir . '/' . $this->stripQueryString($datas));
            }

            $value = $this->folder . '/' . $datas->getFilename();
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        // Handled by the FileListener (on bind event)
        return $value;
    }

    private function stripQueryString($file)
    {
        if (false !== ($pos = strpos($file, '?'))) {
            $file = substr($file, 0, $pos);
        }

        return $file;
    }
}
