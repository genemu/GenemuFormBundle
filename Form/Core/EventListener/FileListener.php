<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Core\EventListener;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\File;

use Genemu\Bundle\FormBundle\Gd\File\Image;

/**
 * Adds a protocol to a URL if it doesn't already have one.
 *
 * @author Bernhard Schussek <bernhard.schussek@symfony-project.com>
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class FileListener implements EventSubscriberInterface
{
    protected $rootDir;
    protected $multiple;

    /**
     * Constructs
     *
     * @param string  $rootDir
     * @param boolean $multiple
     */
    public function __construct($rootDir, $multiple = false)
    {
        $this->rootDir = $rootDir;
        $this->multiple = $multiple;
    }

    /**
     * {@inheritdoc}
     */
    public function onBind(FormEvent $event)
    {
        $data = $event->getData();

        if (empty($data)) {
            return;
        }

        if ($this->multiple) {
            $paths = explode(',', $data);
            $return = array();

            foreach ($paths as $path) {
                if ($handle = $this->getHandleToPath($path)) {
                    $return[] = $handle;
                }
            }
        } else {
            if ($handle = $this->getHandleToPath($data)) {
                $return = $handle;
            }
        }

        $event->setData($return);
    }

    /**
     * Get Handle to Path
     *
     * @param string $path
     *
     * @return File
     */
    private function getHandleToPath($path)
    {
        $path = $this->rootDir . '/' . $this->stripQueryString($path);

        if (is_file($path)) {
            $handle = new File($path);

            if (preg_match('/image/', $handle->getMimeType())) {
                $handle = new Image($handle->getPathname());
            }

            return $handle;
        }

        return null;
    }

    /**
     * Delete info after `?`
     *
     * @param string $file
     *
     * @return string
     */
    private function stripQueryString($file)
    {
        if (false !== ($pos = strpos($file, '?'))) {
            $file = substr($file, 0, $pos);
        }

        return $file;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(FormEvents::BIND => 'onBind');
    }
}
