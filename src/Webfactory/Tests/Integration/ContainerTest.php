<?php

namespace Webfactory\Tests\Integration;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * Checks the service container.
 */
class ContainerTest extends AbstractContainerTestCase
{
    /**
     * Checks if it is possible to create instances of the defined services.
     */
    public function testServicesCanBeInstantiated()
    {
        $container = $this->getContainer();

        $this->setExpectedException(null);
        foreach ($container->getServiceIds() as $id) {
            /* @var $id string */
            $container->get($id, Container::NULL_ON_INVALID_REFERENCE);
        }
    }

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
     * Checks if registered Twig extensions implement the correct interface.
     *
     * @param string $id
     * @param \Twig_ExtensionInterface|mixed $extension
     * @dataProvider getTwigExtensions
     */
    public function testRegisteredTwigExtensionsImplementCorrectInterface($id = null, $extension = null)
    {
        if ($id === null && $extension === null) {
            $this->markTestSkipped('No twig extensions registered, nothing to test.');
        }
        $message = 'Service "%s" is tagged as Twig extension, but it does not implement the required interface.';
        $message = sprintf($message, $id);
        $this->assertInstanceOf('\Twig_ExtensionInterface', $extension, $message);
    }

    /**
     * Checks if all "@Secure" annotations in the services reference
     * existing roles.
     *
     * @param \ReflectionMethod $method $method
     * @param Secure $annotation
     * @dataProvider secureAnnotationProvider
     */
    public function testSecureAnnotationsReferenceExistingRoles(
        \ReflectionMethod $method = null,
        Secure $annotation = null
    ) {
        if ($method === null && $annotation === null) {
            $this->markTestSkipped('No @Secure annotations found, nothing to test.');
        }
        foreach ($annotation->roles as $role) {
            /* @var $role string */
            $existingRoles = $this->getExistingRoles();
            $message = 'Method %s::%s() references role "%s" via @Secure annotation, '
                     . 'but only the following roles are available: [%s]';
            $message = sprintf(
                $message,
                $method->getDeclaringClass()->getName(),
                $method->getName(),
                $role,
                implode(', ', $existingRoles)
            );
            $this->assertContains($role, $existingRoles, $message);
        }
    }

    /**
     * Provides a set of service methods and the Secure annotations that are assigned.
     *
     * @return array(array(\ReflectionMethod|\JMS\SecurityExtraBundle\Annotation\Secure))
     */
    public function secureAnnotationProvider()
    {
        $records          = array();
        $annotationReader = $this->getAnnotationReader();
        foreach ($this->getServiceClasses() as $class) {
            /* @var $class string */
            $info = new \ReflectionClass($class);
            foreach ($info->getMethods() as $method) {
                /* @var $method \ReflectionMethod */
                /* @var $annotation \JMS\SecurityExtraBundle\Annotation\Secure */
                $annotation = $annotationReader->getMethodAnnotation(
                    $method,
                    '\JMS\SecurityExtraBundle\Annotation\Secure'
                );
                if ($annotation === null) {
                    continue;
                }
                $records[] = array($method, $annotation);
            }
        }
        if (count($records) === 0) {
            // Return a dummy entry, which indicates that the test can be skipped.
            return array(array());
        }
        return $records;
    }

    /**
     * Returns the names of all classes that are used in the service container.
     *
     * @return array(string)
     */
    protected function getServiceClasses()
    {
        $classes = array();
        $builder = $this->getContainerBuilder();
        foreach ($builder->getDefinitions() as $definition) {
            /* @var $definition \Symfony\Component\DependencyInjection\Definition */
            if ($definition->getClass() === null) {
                continue;
            }
            $classes[] = $class = $builder->getParameterBag()->resolveValue($definition->getClass());
        }
        return array_unique($classes);
    }

    /**
     * Returns the registered Twig extensions.
     *
     * @return array(array(string|object|null|array))
     */
    public function getTwigExtensions()
    {
        return $this->getTaggedServices('twig.extension', array('Symfony', 'Doctrine', 'Twig'));
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

    /**
     * Returns record sets of form types and their corresponding aliases.
     *
     * @return array(array(string|object|null|array))
     */
    public function getFormTypes()
    {
        return $this->getTaggedServices('form.type', array('Symfony'));
    }

    /**
     * Returns tagged (custom) services from the service container.
     *
     * The result contains one array for each tag definition.
     * Each array contains the service ID as first, the service
     * as second and the tag definition as third item.
     *
     * @param string $tag
     * @param array(string) $namespacesToSkip A list of namespace prefixes that will be skipped.
     * @return array(array(object|null|string|array(string=>string)))
     */
    protected function getTaggedServices($tag, array $namespacesToSkip = array())
    {
        $container = $this->getContainerBuilder();
        $tagsById  = $container->findTaggedServiceIds($tag);
        if (count($tagsById) === 0) {
            return array(array());
        }
        $servicesAndDefinitions = array();
        foreach ($tagsById as $id => $tagDefinitions) {
            /* @var $id string */
            /* @var $tagDefinitions array(array(string=>string)) */
            if ($container->has($id)) {
                $class = $container->findDefinition($id)->getClass();
                foreach ($namespacesToSkip as $namespacePrefix) {
                    /* @var $namespacePrefix string */
                    if (strpos($class, $namespacePrefix) === 0) {
                        // Skip types that are defined in ignored namespaces.
                        continue 2;
                    }
                }
            }
            foreach ($tagDefinitions as $tagDefinition) {
                /* @var $tagDefinition array(string=>string) */
                $servicesAndDefinitions[] = array(
                    $id,
                    $container->get($id, Container::NULL_ON_INVALID_REFERENCE),
                    $tagDefinition
                );
            }
        }
        return $servicesAndDefinitions;
    }

    /**
     * Returns a list of roles that exist in the system.
     *
     * @return array(string)
     */
    protected function getExistingRoles()
    {
        $hierarchy = $this->getContainer()->getParameter('security.role_hierarchy.roles');
        $roles = array_keys($hierarchy);
        foreach ($hierarchy as $inheritedRoles) {
            /* $inheritedRoles array(string) */
            $roles = array_merge($roles, $inheritedRoles);
        }
        return array_unique($roles);
    }

    /**
     * Reads the container debug dump and creates a container builder.
     *
     * The container builder must be used to get information about tagged services.
     *
     * @return ContainerBuilder
     */
    protected function getContainerBuilder()
    {
        $containerDebugDefinition = $this->getContainer()->getParameter('debug.container.dump');
        if (!is_file($containerDebugDefinition)) {
            $message = 'This test requires the container debug dump.';
            $this->markTestSkipped($message);
        }
        $container = new ContainerBuilder();
        $loader = new XmlFileLoader($container, new FileLocator());
        $loader->load($containerDebugDefinition);
        return $container;
    }
}
