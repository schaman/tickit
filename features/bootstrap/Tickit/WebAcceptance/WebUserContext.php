<?php

namespace Tickit\WebAcceptance;

use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Web user context.
 *
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class WebUserContext extends MinkContext implements KernelAwareInterface
{
    /**
     * The application kernel
     *
     * @var KernelInterface
     */
    private $kernel;

    /**
     * Sets Kernel instance.
     *
     * @param KernelInterface $kernel HttpKernel instance
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Opens specified page.
     *
     * @Given /^(?:|I )am currently on "(?P<page>[^"]+)"$/
     * @When /^(?:|I )navigate to "(?P<page>[^"]+)"$/
     */
    public function visit($page)
    {
        $this->getSession()->visit($this->locatePath($page));
        $this->getSession()->wait(15000, 'typeof $ != undefined && $("#spin-wrap").length === 0');
    }
}
