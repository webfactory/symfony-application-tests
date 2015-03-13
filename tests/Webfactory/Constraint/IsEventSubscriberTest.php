<?php

namespace Webfactory\Constraint;

/**
 * Tests the event subscriber constraint.
 */
class IsEventSubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Ensures that the constraint fails if the subscriber does not even implement
     * the necessary interface.
     */
    public function testFailsIfInterfaceIsNotImplemented()
    {

    }

    /**
     * Ensures that the check fails if getSubscribedEvents() does not return an array.
     */
    public function testFailsIfGetSubscribedEventsDoesNotReturnArray()
    {

    }

    /**
     * Ensures that the validation detects a not existing event method.
     */
    public function testFailsIfReferenceMethodDoesNotExist()
    {

    }

    /**
     * Ensures that the validation fails if a referenced event method exists,
     * but is not externally callable.
     */
    public function testFailsIfReferencedMethodIsNotPublic()
    {

    }

    /**
     * Ensures that the validation fails if an invalid priority is given.
     */
    public function testFailsIfInvalidPriorityIsGiven()
    {

    }

    /**
     * Checks if a subscriber with simple event method references is accepted:
     *
     * - array('eventName' => 'methodName')
     */
    public function testAcceptsSimpleSubscriber()
    {

    }

    /**
     * Checks if a subscriber that references methods and assigns priorities
     * is accepted:
     *
     * - array('eventName' => array('methodName', $priority))
     */
    public function testAcceptsSubscriberWithPriority()
    {

    }

    /**
     * Checks if a subscriber that assigns multiple methods to an event is accepted:
     *
     * - array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     */
    public function testAcceptsSubscriberWithComplexReferences()
    {

    }
}
