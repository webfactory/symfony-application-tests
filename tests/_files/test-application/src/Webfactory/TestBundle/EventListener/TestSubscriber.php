<?php

namespace Webfactory\TestBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber that is registered for testing.
 */
class TestSubscriber implements EventSubscriberInterface
{
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            'simple'   => 'myEventHook',
            'advanced' => array('myEventHook', 42),
            'complex'  => array(array('myEventHook'), array('myEventHook', 7))
        );
    }

    /**
     * Public hook method.
     */
    public function myEventHook()
    {
    }
}
