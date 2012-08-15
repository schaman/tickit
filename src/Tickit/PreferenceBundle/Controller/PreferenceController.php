<?php

namespace Tickit\PreferenceBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Tickit\CoreBundle\Controller\CoreController;

//bind forms here

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Controller that provides actions for managing system and user preferences
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class PreferenceController extends CoreController
{
    /**
     * @Template("TickitPreferenceBundle:Preference:index.html.twig")
     */
    public function indexAction()
    {
        $user = $this->_getCurrentUser();

        $preferences = $this->getDoctrine()
                            ->getRepository('TickitPreferenceBundle:Preference')
                            ->findForUser($user);

        var_dump($preferences);
    }

}