<?php

namespace Webfactory\Tests\Integration;

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
     * @param string|null $id The ID of the type service.
     * @param \Symfony\Component\Form\FormTypeInterface|mixed $type The type service from the container.
     * @param array(string=>string) $tagDefinition The tag definition that is assigned to the type.
     * @dataProvider getFormTypes
     */
    public function testFormTypeAliasAndNameAreEqual($id = null, $type = null, array $tagDefinition = array())
    {
        if ($id === null && $type === null) {
            $this->markTestSkipped('No form types registered, nothing to test.');
        }
        $message = 'An alias must be defined for form type "%s".';
        $message = sprintf($message, $id);
        $this->assertArrayHasKey('alias', $tagDefinition, $message);

        /* @var $type \Symfony\Component\Form\FormTypeInterface */
        $message = 'Service "%s" is tagged as form type, but it does not implement the required interface.';
        $message = sprintf($message, $id);
        $this->assertInstanceOf('\Symfony\Component\Form\FormTypeInterface', $type, $message);

        $message = 'Form type name and assigned alias must match, but service "%s" '
                 . 'uses "%s" as name and "%s" as alias.';
        $message = sprintf($message, $id, $type->getName(), $tagDefinition['alias']);
        $this->assertEquals($type->getName(), $tagDefinition['alias'], $message);
    }

    /**
     * Returns record sets of form types and their corresponding aliases.
     *
     * @return array(array(string|object|null|array))
     */
    public function getFormTypes()
    {
        return $this->getTaggedServices('form.type', array('Symfony'));
    }
}
