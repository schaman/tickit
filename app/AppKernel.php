<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Hearsay\RequireJSBundle\HearsayRequireJSBundle(),
            new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            // ---- Tickit Bundles Below Here ----
            new Tickit\Bundle\AssetsBundle\TickitAssetsBundle(),
            new Tickit\Bundle\CoreBundle\TickitCoreBundle(),
            new Tickit\Bundle\NavigationBundle\TickitNavigationBundle(),
            new Tickit\Bundle\PermissionBundle\TickitPermissionBundle(),
            new Tickit\Bundle\UserBundle\TickitUserBundle(),
            new Tickit\Bundle\PreferenceBundle\TickitPreferenceBundle(),
            new Tickit\Bundle\IssueBundle\TickitIssueBundle(),
            new Tickit\Bundle\ProjectBundle\TickitProjectBundle(),
            new Tickit\Bundle\DashboardBundle\TickitDashboardBundle(),
            new Tickit\Bundle\NotificationBundle\TickitNotificationBundle(),
            new Tickit\Bundle\ClientBundle\TickitClientBundle(),
            new Tickit\Bundle\FilterBundle\TickitFilterBundle(),
            new Tickit\Bundle\PaginationBundle\TickitPaginationBundle(),
            new Tickit\Bundle\SearchBundle\TickitSearchBundle(),
            new Tickit\Bundle\PickerBundle\TickitPickerBundle(),
            new Tickit\Bundle\SecurityBundle\TickitSecurityBundle()
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
