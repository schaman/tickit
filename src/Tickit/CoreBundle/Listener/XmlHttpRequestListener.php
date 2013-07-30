<?php

namespace Tickit\CoreBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Tickit\CoreBundle\Controller\DefaultController;

/**
 * XmlHttpRequest listener.
 *
 * The purpose of this listener is to filter controller actions away from
 * the targeted routes if the request is not an XmlHttpRequest.
 *
 * Tickit serves a single page via HTTP and is rendered client-side, so we
 * don't want these requests hitting the target controller actions. Instead
 * we direct them to a dummy controller action that renders the base layout.
 *
 * @deprecated This can probably be removed pending discussions with @markwilson
 *
 * @package Tickit\CoreBundle\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class XmlHttpRequestListener
{
    protected $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Called before a controller action is invoked.
     *
     * Here we can determine whether the request is an XmlHttpRequest and
     * re-set the controller to our dummy target.
     *
     * @param FilterControllerEvent $event The controller event object
     *
     * @return void
     */
    public function beforeControllerAction(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        $isXmlHttpRequest = $event->getRequest()->isXmlHttpRequest();
        $isMasterRequest = (HttpKernel::MASTER_REQUEST === $event->getRequestType());

        if ($isMasterRequest && false === $isXmlHttpRequest && false === $this->isAllowed($controller[0])) {
            $controller = new DefaultController();
            $controller->setContainer($this->container);
            $event->setController(array($controller, 'defaultAction'));
        }
    }

    /**
     * White-lists controllers that are allowed HTTP requests.
     *
     * TODO: Maybe this would be better as a blacklist, so we add our own application controllers??
     *
     * @param $controller
     * @return bool
     */
    private function isAllowed($controller)
    {
        $allowed = array(
            'Symfony\Bundle\AsseticBundle\Controller\AsseticController',
            'Symfony\Bundle\WebProfilerBundle\Controller\ExceptionController',
            'Symfony\Bundle\WebProfilerBundle\Controller\ProfilerController',
            'Symfony\Bundle\WebProfilerBundle\Controller\RouterController'
        );

        return in_array(get_class($controller), $allowed);
    }
}
