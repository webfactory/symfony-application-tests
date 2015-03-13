<?php

namespace Webfactory\Constraint;

/**
 * Checks if an object is a correctly configured Symfony event subscriber.
 */
class IsEventSubscriber extends \PHPUnit_Framework_Constraint
{
    /**
     * Checks if the given object is an event subscriber.
     *
     * @param  mixed $other
     * @return boolean
     */
    protected function matches($other)
    {
        return parent::matches($other);
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        // TODO: Implement toString() method.
    }
}
