<?php

namespace Webfactory\TestBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Custom validation constraint that references a validator, which is configured as service.
 */
class Custom extends Constraint
{
    /**
     * References the corresponding validator.
     *
     * @return string
     */
    public function validatedBy()
    {
        return 'webfactory_test.validator.custom';
    }
}
