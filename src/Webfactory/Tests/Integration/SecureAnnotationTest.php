<?php

namespace Webfactory\Tests\Integration;

use Doctrine\Common\Util\ClassUtils;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;
use Webfactory\Util\DataProviderArgumentIterator;
use Webfactory\Util\DataProviderIterator;

/**
 * Tests the @Secure annotations that are used in the application.
 */
class SecureAnnotationTest extends AbstractContainerTestCase
{
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
     * Ensures that controllers, which are not defined as service, do not use
     * "secure" annotations (these would be ignored).
     *
     * @param string $class The name of the controller class.
     * @dataProvider nonServiceControllerClassNameProvider
     */
    public function testNonServiceControllerDoesNotUseSecureAnnotations($class = null)
    {
        if ($class === null) {
            $this->markTestSkipped('No controllers found that are not registered as service. Nothing to test.');
        }
        $info = new \ReflectionClass($class);
        $methodsWithAnnotation = array();
        foreach ($info->getMethods() as $method) {
            /* @var $method \ReflectionMethod */
            /* @var $annotation \JMS\SecurityExtraBundle\Annotation\Secure */
            $annotation = $this->getAnnotationReader()->getMethodAnnotation(
                $method,
                '\JMS\SecurityExtraBundle\Annotation\Secure'
            );
            if ($annotation !== null) {
                $methodsWithAnnotation[] = $method->getName();
            }
        }
        $message = 'Controller %s is not defined as a service, '
            . 'but the following methods use @Secure annotations. ' .PHP_EOL
            . 'These annotations work only for services:' . PHP_EOL
            . implode(PHP_EOL, $methodsWithAnnotation);
        $message = sprintf($message, $class);
        $this->assertCount(0, $methodsWithAnnotation, $message);
    }

    /**
     * Provides a set of service methods and the Secure annotations that are assigned.
     *
     * @return \Traversable
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
        return new DataProviderIterator($records);
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
            $classes[] = $builder->getParameterBag()->resolveValue($definition->getClass());
        }
        return array_unique($classes);
    }

    /**
     * Returns a list of roles that exist in the system.
     *
     * @return array(string)
     */
    protected function getExistingRoles()
    {
        $container = $this->getContainer();
        $message = 'Cannot read roles from parameter "security.role_hierarchy.roles". '
                 . 'Did you configure the security system?';
        $this->assertTrue($container->hasParameter('security.role_hierarchy.roles'), $message);
        $hierarchy = $container->getParameter('security.role_hierarchy.roles');
        $roles = array_keys($hierarchy);
        foreach ($hierarchy as $inheritedRoles) {
            /* $inheritedRoles array(string) */
            $roles = array_merge($roles, $inheritedRoles);
        }
        return array_unique($roles);
    }

    /**
     * Returns the class names of all controllers that are not defined as a service.
     *
     * @return \Traversable
     */
    public function nonServiceControllerClassNameProvider()
    {
        $classes = array();
        foreach ($this->getControllerDefinitions() as $definition) {
            /* @var $definition \stdClass */
            if ($definition->isService) {
                continue;
            }
            $classes[] = $definition->class;
        }
        $classes = array_unique($classes);
        $data    = new DataProviderArgumentIterator($classes);
        return new DataProviderIterator($data);
    }

    /**
     * Returns information about controllers that are used in the application.
     *
     * The data entries are simple objects that provide the following attributes:
     *
     * - isService (boolean) - Determines if the controller is configured as service.
     * - class (string)      - The (original) class name of the controller.
     * - controller (object) - The instantiated controller.
     *
     * @return array(object)
     */
    protected function getControllerDefinitions()
    {
        if (!$this->getContainer()->has('router')) {
            return array();
        }
        $routes      = $this->getContainer()->get('router')->getRouteCollection()->all();
        $definitions = array();
        foreach ($routes as $route) {
            /* @var $route \Symfony\Component\Routing\Route */
            $assignedController = $route->getDefault('_controller');
            if ($assignedController === null) {
                // No controller is assigned to this route.
                continue;
            }
            if (strpos($assignedController, 'assetic.') === 0) {
                // Ignore Assetic controllers.
                continue;
            }
            $controller = $this->createController($assignedController);
            if ($controller === null) {
                continue;
            }
            $definition = new \stdClass();
            $definition->isService  = substr_count($assignedController, ':') === 1;
            $definition->class      = $this->toClassName($controller);
            $definition->controller = $controller;
            $definitions[] = $definition;
        }
        return $definitions;
    }

    /**
     * Uses the provided controller to determine the original controller class name.
     *
     * Controllers might be encapsulated by proxies, which requires a special treatment.
     *
     * @param object $controller
     * @return string
     */
    protected function toClassName($controller)
    {
        return ClassUtils::getClass($controller);
    }

    /**
     * Uses the route reference to create a controller object.
     *
     * @param mixed $reference The controller reference from a route.
     * @return object|null
     */
    protected function createController($reference)
    {
        /* @var $resolver \Symfony\Component\HttpKernel\Controller\ControllerResolverInterface */
        $resolver         = $this->getContainer()->get('debug.controller_resolver');
        $simulatedRequest = new Request(array(), array(), array('_controller' => $reference));
        $controller       = $resolver->getController($simulatedRequest);
        if (is_array($controller) && is_object($controller[0])) {
            $controller = $controller[0];
        }
        if (!is_object($controller)) {
            // Controller might be callable, which is not of interest for us.
            return null;
        }
        return $controller;
    }
}
