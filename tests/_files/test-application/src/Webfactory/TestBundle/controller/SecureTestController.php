<?php

namespace Webfactory\TestBundle\controller;

use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Controller that is configured as a service and references the Admin role.
 */
class SecureTestController
{
    /**
     * @Secure(roles="ROLE_ADMIN")
     */
    public function securityAction()
    {
    }
}
