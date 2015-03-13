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
            $this->checkListener($event, $subscription);
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
     * @param string $event
     * @param mixed $listener
     */
    protected function checkListener($event, $listener)
    {
        if (is_string($listener)) {
            // Add the default priority and use the default validation.
            $listener = array($listener, 0);
        }
        if (!is_array($listener)) {
            $this->addProblem(sprintf('Listener definition for event "%s" must be an array or a string.', $event));
            return;
        }

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
