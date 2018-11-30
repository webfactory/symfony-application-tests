<?php

namespace Webfactory\Tests\Integration;

use Symfony\Component\HttpKernel\Kernel;
use Webfactory\Util\ServiceCreator;
use Webfactory\Util\TaggedService;

/**
 * Tests the registered validators.
 */
class ValidatorTest extends AbstractContainerTestCase
{
    /**
     * Checks if the validators that are configured in the container implement
     * the correct interface.
     *
     * @param TaggedService $service
     * @dataProvider getValidators
     */
    public function testRegisteredValidatorsImplementCorrectInterface(TaggedService $service = null)
    {
        if ($service === null) {
            // No validators registered, nothing to test.
            return;
        }

        $creator = new ServiceCreator($this->getContainer());
        /* @var $validator \Symfony\Component\Validator\ConstraintValidatorInterface */
        $validator = $creator->create($service->getServiceId());

        $message = 'Service "%s" is tagged as validator, but it does not implement the required interface.';
        $message = sprintf($message, $service->getServiceId());
        $this->assertInstanceOf('\Symfony\Component\Validator\ConstraintValidatorInterface', $validator, $message);
    }

    /**
     * Checks if the validators that are configured in the container are aliased in Symfony Versions < 2.7.
     *
     * @param TaggedService $service
     * @dataProvider getValidators
     * @doesNotPerformAssertions
     */
    public function testRegisteredValidatorsAreAliasedInOlderSymfonyVersions(TaggedService $service = null)
    {
        if ($service === null) {
            // No validators registered, nothing to test.
            return;
        }

        if (
            defined('Symfony\\Component\\HttpKernel\\Kernel::VERSION')
            && version_compare(Kernel::VERSION, '2.7', '>=')
        ) {
            // As of Symfony 2.7, validator services don't need to be aliased in every case.
            return;
        }

        $tagDefinition = $service->getTagDefinition();
        $message = 'An alias must be defined for validation service "%s".';
        $message = sprintf($message, $service->getServiceId());
        $this->assertArrayHasKey('alias', $tagDefinition, $message);
    }

    /**
     * Returns services that are tagged as validators.
     *
     * @return \Traversable
     */
    public function getValidators()
    {
        return $this->getTaggedServices('validator.constraint_validator');
    }
}
