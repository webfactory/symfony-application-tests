<?php

namespace Webfactory\Constraint;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Checks if an object is a correctly configured Symfony event subscriber.
 */
class IsEventSubscriber extends \PHPUnit_Framework_Constraint
{
    /**
     * List of detected problems.
     *
     * @var string[]
     */
    private $detectedProblems = array();

    /**
     * Checks if the given object is an event subscriber.
     *
     * @param  mixed $other
     * @return boolean
     */
    protected function matches($other)
    {
        $this->resetProblems();
        if (!($other instanceof EventSubscriberInterface)) {
            $this->addProblem('Subscriber must implement \Symfony\Component\EventDispatcher\EventSubscriberInterface.');
            return false;
        }
        $subscribedEvents = call_user_func(array(get_class($other), 'getSubscribedEvents'));
        if (!is_array($subscribedEvents)) {
            $this->addProblem('getSubscribedEvents() must return an array.');
            return false;
        }
        foreach ($subscribedEvents as $event => $subscription) {
            /* @var $event string */
            /* @var $subscription mixed|string|array */
            $this->checkListener($other, $event, $subscription);
        }
        return !$this->hasProblems();
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'is a valid EventSubscriber.';
    }

    /**
     * Checks if the listener definition for the given event is valid.
     *
     * @param EventSubscriberInterface $subscriber
     * @param string $event
     * @param mixed $listener
     */
    protected function checkListener(EventSubscriberInterface $subscriber, $event, $listener)
    {
        if (is_string($listener)) {
            // Add the default priority and use the default validation.
            $listener = array($listener, 0);
        }
        if (!is_array($listener)) {
            $this->addProblem(sprintf('Listener definition for event "%s" must be an array or a string.', $event));
            return;
        }
        if (count($listener) === 1) {
            // Method without priority.
            $listener[] = 0;
        }
        if ($this->containsSeveralSubscriptions($listener)) {
            foreach ($listener as $subListener) {
                $this->checkListener($subscriber, $event, $subListener);
            }
            return;
        }
        if (count($listener) !== 2) {
            $message = 'Listener definition for event "%s" must consist of a method and a priority, but received: %s';
            $this->addProblem(sprintf($message, $event, $this->exporter->export($listener)));
            return;
        }
        list($method, $priority) = $listener;
        $this->checkMethod($subscriber, $event, $method);
        $this->checkPriority($event, $priority);
    }

    /**
     * Checks the given subscriber method.
     *
     * @param EventSubscriberInterface $subscriber
     * @param string $event
     * @param string|mixed $method
     */
    protected function checkMethod($subscriber, $event, $method)
    {
        if (!is_string($method)) {
            $message = 'Listener definition for event "%s" contains an invalid method reference: %s';
            $this->addProblem(sprintf($message, $event, $this->exporter->export($method)));
            return;
        }
        if (!method_exists($subscriber, $method)) {
            $message = 'Listener definition for event "%s" references method "%s", '
                     . 'but the method does not exist on subscriber.';
            $this->addProblem(sprintf($message, $event, $method));
            return;
        }
        if (!is_callable(array($subscriber, $method))) {
            $message = 'Listener definition for event "%s" references method "%s", '
                     . 'but the method is not publicly accessible.';
            $this->addProblem(sprintf($message, $event, $method));
            return;
        }
    }

    /**
     * Checks the given priority.
     *
     * @param string $event
     * @param integer|mixed $priority
     */
    protected function checkPriority($event, $priority)
    {
        if (!is_int($priority)) {
            $message = 'Priority for event "%s" must be an integer, but received: %s';
            $this->addProblem(sprintf($message, $event, $this->exporter->export($priority)));
            return;
        }
    }

    /**
     * Checks if the given subscriptions list contains only arrays (which means that
     * it contains several descriptions).
     *
     * @param array(mixed) $subscriptions
     * @return boolean
     */
    protected function containsSeveralSubscriptions(array $subscriptions)
    {
        foreach ($subscriptions as $subscription) {
            /* @var mixed */
            if (!is_array($subscription)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Adds a problem to the list.
     *
     * @param string $description
     */
    protected function addProblem($description)
    {
        $this->detectedProblems[] = $description;
    }

    /**
     * Checks if problems have been detected.
     *
     * @return boolean
     */
    protected function hasProblems()
    {
        return count($this->detectedProblems) > 0;
    }

    /**
     * Resets the list of problems.
     */
    protected function resetProblems()
    {
        $this->detectedProblems = array();
    }

    /**
     * Return additional failure description where needed
     *
     * The function can be overridden to provide additional failure
     * information like a diff
     *
     * @param  mixed  $other Evaluated value or object.
     * @return string
     */
    protected function additionalFailureDescription($other)
    {
        $problems = array_map(function ($problem) {
            return '- ' . $problem;
        }, $this->detectedProblems);
        return implode(PHP_EOL, $problems);
    }
}
