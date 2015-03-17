<?php

namespace Webfactory\Tests\Integration;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webfactory\Constraint\IsEventSubscriberConstraint;
use Webfactory\Util\ServiceCreator;
use Webfactory\Util\TaggedService;

/**
 * Checks registered event subscribers.
 */
class EventSubscriberTest extends AbstractContainerTestCase
{
    /**
     * Checks if registered event subscribers use correct method references.
     *
     * @param TaggedService $service
     * @dataProvider getEventSubscribers
     */
    public function testRegisteredEventSubscribers(TaggedService $service = null)
    {
        if ($service === null) {
            $this->markTestSkipped('No event subscribers registered, nothing to test.');
        }

        $creator = new ServiceCreator($this->getContainer());
        /* @var $subscriber EventSubscriberInterface */
        $subscriber = $creator->create($service->getServiceId());
        $message = 'Service "%s" is not a valid event subscriber.';
        $this->assertThat($subscriber, new IsEventSubscriberConstraint(), sprintf($message, $service->getServiceId()));
    }

    /**
     * Returns the registered event subscribers.
     *
     * @return \Traversable
     */
    public function getEventSubscribers()
    {
        return $this->getTaggedServices('kernel.event_subscriber');
    }
}
