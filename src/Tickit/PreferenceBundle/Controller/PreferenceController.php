<?php

namespace Tickit\PreferenceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

//bind forms here

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PreferenceController extends Controller
{
    /**
     * @Template("TickitPreferenceBundle:Preference:index.html.twig")
     */
    public function indexAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();

        $preferences = $this->getDoctrine()
                            ->getRepository('TickitPreferenceBundle:Preference')
                            ->findForUser($user);

        var_dump($preferences);
    }

}