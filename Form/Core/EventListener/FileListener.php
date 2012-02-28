<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Core\EventListener;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Event\DataEvent;
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
    public function onBindNormData(DataEvent $event)
    {
        $data = $event->getData();

        if (true === empty($data)) {
            return;
        }

        $return = null;
        if (true === $this->multiple) {
            $paths = explode(',', $data);
            $return = array();

            foreach ($paths as $path) {
                if (null !== ($handle = $this->getHandleToPath($path))) {
                    $return[] = $handle;
                }
            }
        } else {
            if (null !== ($handle = $this->getHandleToPath($data))) {
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

        if (true === is_file($path)) {
            $handle = new File($path);

            if (true === preg_match('/image/', $handle->getMimeType())) {
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
    static public function getSubscribedEvents()
    {
        return array(FormEvents::BIND_NORM_DATA => 'onBindNormData');
    }
}
