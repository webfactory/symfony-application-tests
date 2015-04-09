<?php

namespace Webfactory\TestBundle\controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller that is configured as a service and references the Admin role.
 */
class SecureTestController
{
    /**
     * @return array
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function securityAction()
    {
        return array();
    }

    /**
     * @return Response
     * @Secure(roles="IS_AUTHENTICATED_ANONYMOUSLY")
     */
    public function anonymousAction()
    {
        return new Response();
    }

    /**
     * @return Response
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
    public function rememberedAction()
    {
        return new Response();
    }

    /**
     * @return Response
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function fullyAuthenticatedAction()
    {
        return new Response();
    }
}
