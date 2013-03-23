<?php

namespace Tickit\PreferenceBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Tickit\CoreBundle\Controller\AbstractCoreController;

//bind forms here

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Controller that provides actions for managing system and user preferences
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class PreferenceController extends AbstractCoreController
{
    /**
     * Index action that lists all preferences for editing (should this just be editAction??)
     *
     * @Template("TickitPreferenceBundle:Preference:index.html.twig")
     */
    public function indexAction()
    {
        $user = $this->getCurrentUser();

        $preferences = $this->getDoctrine()
                            ->getRepository('TickitPreferenceBundle:Preference')
                            ->findForUser($user);

        var_dump($preferences);
    }

}