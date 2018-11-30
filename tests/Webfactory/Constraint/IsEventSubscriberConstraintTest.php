<?php

namespace Webfactory\Constraint;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\LogicalNot;
use Webfactory\Constraint\Test\TestSubscriber;

/**
 * Tests the event subscriber constraint.
 */
class IsEventSubscriberConstraintTest extends TestCase
{
    /**
     * System under test.
     *
     * @var IsEventSubscriberConstraint
     */
    protected $constraint = null;

    /**
     * Initializes the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->constraint = new IsEventSubscriberConstraint();
    }

    /**
     * Cleans up the test environment.
     */
    protected function tearDown()
    {
        $this->constraint = null;
        parent::tearDown();
    }

    /**
     * Ensures that the constraint rejects non-objects.
     */
    public function testFailsIfPrimitiveTypeIsProvided()
    {
        $this->assertRejected(42);
    }

    /**
     * Ensures that the constraint fails if the subscriber does not even implement
     * the necessary interface.
     */
    public function testFailsIfInterfaceIsNotImplemented()
    {
        $this->assertRejected(new \stdClass());
    }

    /**
     * Ensures that the check fails if getSubscribedEvents() does not return an array.
     */
    public function testFailsIfGetSubscribedEventsDoesNotReturnArray()
    {
        $subscriber = new TestSubscriber(new \stdClass());

        $this->assertRejected($subscriber);
    }

    /**
     * Ensures that the check fails if the given method reference is not valid.
     */
    public function testFailsIfInvalidMethodReferenceIsProvided()
    {
        $subscriber = new TestSubscriber(array('event' => array(new \stdClass(), 0)));

        $this->assertRejected($subscriber);
    }

    /**
     * Ensures that the validation detects a not existing event method.
     */
    public function testFailsIfReferencedMethodDoesNotExist()
    {
        $subscriber = new TestSubscriber(array('event' => 'doesNotExist'));

        $this->assertRejected($subscriber);
    }

    /**
     * Ensures that the validation fails if a referenced event method exists,
     * but is not externally callable.
     */
    public function testFailsIfReferencedMethodIsNotPublic()
    {
        $subscriber = new TestSubscriber(array('event' => 'internalMethod'));

        $this->assertRejected($subscriber);
    }

    /**
     * Ensures that the validation fails if an invalid priority is given.
     */
    public function testFailsIfInvalidPriorityIsGiven()
    {
        $subscriber = new TestSubscriber(array('event' => array('hookMethod', new \stdClass())));

        $this->assertRejected($subscriber);
    }

    /**
     * Checks if a subscriber with simple event method references is accepted:
     *
     * - array('eventName' => 'methodName')
     */
    public function testAcceptsSimpleSubscriber()
    {
        $subscriber = new TestSubscriber(array('event' => 'hookMethod'));

        $this->assertAccepted($subscriber);
    }

    /**
     * Checks if a subscriber that references methods and assigns priorities
     * is accepted:
     *
     * - array('eventName' => array('methodName', $priority))
     */
    public function testAcceptsSubscriberWithPriority()
    {
        $subscriber = new TestSubscriber(array('event' => array('hookMethod', 42)));

        $this->assertAccepted($subscriber);
    }

    /**
     * Checks if a subscriber that assigns multiple methods to an event is accepted:
     *
     * - array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     */
    public function testAcceptsSubscriberWithComplexReferences()
    {
        $subscriber = new TestSubscriber(array('event' => array(array('hookMethod'), array('anotherHookMethod', 42))));

        $this->assertAccepted($subscriber);
    }

    /**
     * Checks if a method list with a single prioritized entry is accepted:
     *
     * - array('eventName' => array(array('methodName1', $priority))
     *
     * @see https://github.com/webfactory/symfony-application-tests/issues/27
     */
    public function testAcceptsSubscriberWithWithSinglePrioritizedReferenceInList()
    {
        $subscriber = new TestSubscriber(array('event' => array(array('hookMethod', 42))));

        $this->assertAccepted($subscriber);
    }

    /**
     * Asserts that the given subscriber is accepted.
     *
     * @param mixed $subscriber
     */
    protected function assertAccepted($subscriber)
    {
        $this->assertThat($subscriber, new IsEventSubscriberConstraint());
    }

    /**
     * Asserts that the given subscriber is rejected.
     *
     * @param mixed $subscriber
     */
    protected function assertRejected($subscriber)
    {
        $this->assertThat($subscriber, new LogicalNot(new IsEventSubscriberConstraint()));
    }
}
