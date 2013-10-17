<?php

namespace Tickit\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Controller\SecurityController as BaseController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Security controller.
 *
 * Overrides functionality provided by FOSUserBundle so that we can add custom logic to login related actions
 *
 * @package Tickit\UserBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class SecurityController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    protected function renderLogin(array $data)
    {
        $engine = $this->container->getParameter('fos_user.template.engine');
        $template = sprintf('TickitUserBundle:Security:login.html.%s', $engine);

        return $this->container->get('templating')->renderResponse($template, $data);
    }
}
