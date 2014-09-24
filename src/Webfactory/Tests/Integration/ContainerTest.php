<?php

namespace Webfactory\Tests\Integration;

use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Checks the service container.
 */
class ContainerTest extends AbstractContainerTestCase
{
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
        return $this->addFallbackEntryToProviderDataIfNecessary($records);
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
}
