<?php

namespace Tickit\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('TickitClientBundle:Default:index.html.twig', array('name' => $name));
    }
}
