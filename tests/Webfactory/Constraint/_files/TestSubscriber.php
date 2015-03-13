<?php

namespace Webfactory\Constraint\Test;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event subscriber that is used for testing.
 *
 * Due to the use of static methods, only one instance should be created
 * at a time.
 * The explicit definition of this class avoid the need for mocking static
 * methods.
 */
class TestSubscriber implements EventSubscriberInterface
{
    /**
     * Definition of subscribed events.
     *
     * @var mixed
     */
    protected static $subscribedEvents = null;

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
        return self::$subscribedEvents;
    }

    /**
     * @param mixed $subscribedEvents
     */
    public function __construct($subscribedEvents)
    {
        self::$subscribedEvents = $subscribedEvents;
    }

    /**
     * A public method that can  be used as event listener.
     */
    public function hookMethod()
    {
    }

    /**
     * Another method that can be used as event listener.
     */
    public function anotherHookMethod()
    {
    }

    /**
     * Internal method, cannot be used as event listener.
     */
    protected function internalMethod()
    {
    }
}
