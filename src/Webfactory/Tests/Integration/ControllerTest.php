<?php

namespace Webfactory\Tests\Integration;

use Doctrine\Common\Util\ClassUtils;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tests general configuration of the controller classes.
 */
class ControllerTest extends AbstractContainerTestCase
{
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
     * Returns the class names of all controllers that are not defined as a service.
     *
     * @return array(array(string))
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
        if (count($classes) === 0) {
            // Return a dummy entry to avoid a failing test.
            return array(array());
        }
        $classes = array_unique($classes);
        return array_map(function ($class) {
            return array($class);
        }, $classes);
    }

    /**
     * Returns the annotation reader that is used by the application.
     *
     * @return \Doctrine\Common\Annotations\Reader
     */
    protected function getAnnotationReader()
    {
        return $this->getContainer()->get('annotation_reader');
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
     * Uses the route reference to create an controller object.
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
