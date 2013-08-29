<?php
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
     * @Given /^I am on "([^"]*)"$/
     */
    public function iAmOn($path)
    {
//        $url = sprintf('http://%s%s', $this->getContainerParameter('hostname'), $path);

        $this->getSession()->visit($path);
        $this->getSession()->wait(15000, 'typeof $ == "function"');
    }

    /**
     * @Given /^I should see a "([^"]*)" element$/
     */
    public function iShouldSeeAElement($tagName)
    {
        $success = $this->getSession()->evaluateScript('$("' . $tagName . '").length > 0');

        return $success;
    }
}
