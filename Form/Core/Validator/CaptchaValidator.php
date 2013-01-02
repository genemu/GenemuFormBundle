<?php

/*
 * This file is part of the GenemuFormBundle package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Genemu\Bundle\FormBundle\Form\Core\Validator;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\DataEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;

use Genemu\Bundle\FormBundle\Captcha\CaptchaService;

/**
 * CaptchaValidator
 *
 * @author Olivier Chauvel <olivier@generation-multiple.com>
 */
class CaptchaValidator implements EventSubscriberInterface
{
    /**
     * @var \Genemu\Bundle\FormBundle\Captcha\CaptchaService
     */
    protected $service;

    /**
     * Constructs
     *
     * @param \Genemu\Bundle\FormBundle\Captcha\CaptchaService $service
     */
    public function __construct(CaptchaService $service)
    {
        $this->service          = $service;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(DataEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        $config = $form->getConfig()->getOption('config_name');

        if (false == $this->service->isCodeValid($config, $data)) {
            $form->addError(new FormError('The captcha is invalid.'));
        }
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(FormEvents::POST_BIND => 'validate');
    }
}
