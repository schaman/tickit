<?php

namespace Tickit\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Tickit\CoreBundle\Controller\CoreController;

//bind forms here

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Controller that provides actions to manipulate user entities
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class UserController extends CoreController
{
    /**
     * Lists all users in the system
     *
     * @Template("TickitUserBundle:User:index.html.twig")
     *
     * @return array
     */
    public function indexAction()
    {
        $users = $this->getDoctrine()
                      ->getManager()
                      ->getRepository('TickitUserBundle:User')
                      ->findUsers();

        return array('users' => $users);
    }

}