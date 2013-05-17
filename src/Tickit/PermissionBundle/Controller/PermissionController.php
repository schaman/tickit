<?php

namespace Tickit\PermissionBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tickit\UserBundle\Entity\Group;
use Tickit\UserBundle\Entity\User;

/**
 * Permission controller.
 *
 * Provides actions for interacting with anything permission related in the application.
 *
 * @package Tickit\PermissionBundle\Controller
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class PermissionController extends Controller
{
    /**
     * Permissions listing action.
     *
     * Outputs permission data for a given group ID and user ID.
     *
     * The resultant output is the merging of the group permission data and the user permission data (which
     * are overrides for that specific user within the respective group).
     *
     * @param integer $groupId The group ID to render permissions for
     * @param integer $userId  The user ID to render permissions for (optional)
     *
     * @throws NotFoundHttpException If no user could be found for the given user ID
     *
     * @Template("TickitPermissionBundle:Permission:list.json.twig")
     *
     * @return array
     */
    public function permissionListAction($groupId, $userId = null)
    {
        if (null !== $userId) {
            $user = $this->get('tickit_user.manager')->find($userId);
            if (!$user instanceof User) {
                throw $this->createNotFoundException(sprintf('No user found for given ID (%d)', $userId));
            }
        }

        if (null !== $groupId) {
            $group = $this->get('tickit_user.manager')->findGroup($groupId);
            if (!$group instanceof Group) {
                throw $this->createNotFoundException(sprintf('No group found for given ID (%d)', $groupId));
            }
        }

        //TODO: add output renderer for permission data
        $permissions = $this->get('tickit_permission.manager')->getUserPermissionData($groupId, $userId);

        return array('data' => $permissions);
    }
}
