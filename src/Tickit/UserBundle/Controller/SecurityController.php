<?php

namespace Tickit\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Controller\SecurityController as BaseController;

/**
 * This controller is overriding that provided by FOSUserBundle so that we can add custom logic to login related actions
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class SecurityController extends BaseController
{
    /**
     * Login action that performs user login. Here we can add any custom logic that needs to take place when
     * a user performs login to the system
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        //add our own logic here
        return parent::loginAction($request);
    }

    /**
     * {@inheritdoc}
     */
    protected function renderLogin(array $data)
    {
        $template = sprintf('TickitUserBundle:Security:login.html.%s', $this->container->getParameter('fos_user.template.engine'));

        return $this->container->get('templating')->renderResponse($template, $data);
    }

}
