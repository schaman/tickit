<?php

namespace Tickit\PermissionBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tickit\CoreBundle\Controller\AbstractCoreController;

/**
 * Controller that provides actions for managing user permissions
 *
 * @package Tickit\PermissionBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserPermissionController extends AbstractCoreController
{
    /**
     * Lists user permissions
     *
     * @Template("TickitPermissionBundle:UserPermission:index.html.twig")
     *
     * @return array
     */
    public function indexAction()
    {
        return array();
    }
}
