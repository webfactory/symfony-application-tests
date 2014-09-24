<?php

namespace Webfactory\TestBundle\controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Controller that is configured as a service and references the Admin role.
 */
class SecureTestController
{
    /**
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function securityAction()
    {
    }
}
