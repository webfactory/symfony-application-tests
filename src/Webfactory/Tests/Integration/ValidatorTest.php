<?php

namespace Webfactory\Tests\Integration;

/**
 * Tests the registered validators.
 */
class ValidatorTest extends AbstractContainerTestCase
{
    /**
     * Checks if the validators that are configured in the container implement
     * the correct interface.
     *
     * @param string $id
     * @param \Symfony\Component\Validator\ConstraintValidatorInterface|mixed $validator
     * @param array(string=>string) $tagDefinition
     * @dataProvider getValidators
     */
    public function testRegisteredValidatorsImplementCorrectInterface(
        $id = null,
        $validator = null,
        array $tagDefinition = array()
    ) {
        if ($id === null && $validator === null) {
            $this->markTestSkipped('No validators registered, nothing to test.');
        }
        $message = 'Service "%s" is tagged as validator, but it does not implement the required interface.';
        $message = sprintf($message, $id);
        $this->assertInstanceOf('\Symfony\Component\Validator\ConstraintValidatorInterface', $validator, $message);

        $message = 'An alias must be defined for validation service "%s".';
        $message = sprintf($message, $id);
        $this->assertArrayHasKey('alias', $tagDefinition, $message);
    }

    /**
     * Returns services that are tagged as validators.
     *
     * @return array(array(string|object|null|array))
     */
    public function getValidators()
    {
        return $this->getTaggedServices('validator.constraint_validator', array('Symfony'));
    }
}
