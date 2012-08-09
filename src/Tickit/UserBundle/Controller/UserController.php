<?php

namespace Tickit\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

//bind forms here

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UserController extends Controller
{

    /**
     * Returns an instance of the user manager
     *
     * @return \FOS\UserBundle\Entity\UserManager;
     */
    protected function _getUserManager()
    {
        return $this->container->get('fos_user.user_manager');
    }


    /**
     * Lists all users in the system
     *
     * @Template("TickitUserBundle:User:index.html.twig")
     */
    public function indexAction()
    {
        //$userManager = $this->_getUserManager();
        //$user = $userManager->findUserByEmail('');
        return array();
    }

}