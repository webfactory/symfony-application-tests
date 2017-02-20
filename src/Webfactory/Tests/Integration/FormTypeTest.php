<?php

namespace Webfactory\Tests\Integration;

use Symfony\Component\HttpKernel\Kernel;
use Webfactory\Util\ServiceCreator;
use Webfactory\Util\TaggedService;

/**
 * Checks if form types are configured correctly.
 */
class FormTypeTest extends AbstractContainerTestCase
{
    /**
     * Checks if the aliases for form types and their names are equal.
     *
     * Form types cannot be used if this is not the case.
     *
     * @param TaggedService|null $service
     * @dataProvider getFormTypes
     */
    public function testFormTypeAliasAndNameAreEqual(TaggedService $service = null)
    {
        if ($service === null) {
            // No form types registered, nothing to test.
            return;
        }

        $creator = new ServiceCreator($this->getContainer());
        /* @var $type \Symfony\Component\Form\FormTypeInterface */
        $type = $creator->create($service->getServiceId());
        $message = 'Service "%s" is tagged as form type, but it does not implement the required interface.';
        $message = sprintf($message, $service->getServiceId());
        $this->assertInstanceOf('\Symfony\Component\Form\FormTypeInterface', $type, $message);


        // The alias constraint is only relevant for form types in Symfony < 2.8.
        // Newer Symfony version do not use the alias and reference form types by class name.
        if (version_compare(Kernel::VERSION, '2.8.0', '<')) {
            $tagDefinition = $service->getTagDefinition();
            $message = 'An alias must be defined for form type "%s".';
            $message = sprintf($message, $service->getServiceId());
            $this->assertArrayHasKey('alias', $service->getTagDefinition(), $message);

            $message = 'Form type name and assigned alias must match, but service "%s" '
                     . 'uses "%s" as name and "%s" as alias.';
            $message = sprintf($message, $service->getServiceId(), $type->getName(), $tagDefinition['alias']);
            $this->assertEquals($type->getName(), $tagDefinition['alias'], $message);
        }
    }

    /**
     * Returns record sets of form types and their corresponding aliases.
     *
     * @return \Traversable
     */
    public function getFormTypes()
    {
        return $this->getTaggedServices('form.type');
    }
}
