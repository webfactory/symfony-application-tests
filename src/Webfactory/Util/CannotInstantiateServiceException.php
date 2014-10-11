<?php

namespace Webfactory\Util;

/**
 * Exception that is used if a service cannot be created.
 */
class CannotInstantiateServiceException extends \RuntimeException
{
    /**
     * Creates an exception, which indicates that it was not possible to create
     * the service with the given ID.
     *
     *
     * @param string $serviceId
     * @param integer $code
     * @param \Exception $previous The original creation failure.
     */
    public function __construct($serviceId, $code = 0, \Exception $previous = null)
    {
        $message = 'Cannot create service "' . $serviceId . '".';
        parent::__construct($message, $code, $previous);
    }
}
