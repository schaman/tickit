<?php

namespace Tickit\WebAcceptance;

use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Tickit\WebAcceptance\Mixins\ContainerMixin;

/**
 * Web user context.
 *
 * @package Tickit\WebAcceptance
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class WebUserContext extends MinkContext implements KernelAwareInterface
{
    use ContainerMixin;

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

    /**
     * @Given /^I should wait and see "([^"]*)"$/
     */
    public function iShouldWaitAndSee($text)
    {
        $this->spin(function(WebUserContext $context) use ($text) {
            return $context->getSession()->getPage()->hasContent($text);
        });
    }

    /**
     * @Then /^I should wait and see a "([^"]*)" element$/
     */
    public function iShouldWaitAndSeeAElement($selector)
    {
        $this->spin(function(WebUserContext $context) use ($selector) {
            return $context->getSession()->getPage()->find('css', $selector);
        });
    }

    /**
     * @Given /^I should be logged in$/
     */
    public function iShouldBeLoggedIn()
    {
        if (!$this->getSecurityContext()->isGranted('ROLE_USER')) {
            throw new AuthenticationException('User not authenticated');
        }
    }

    /**
     * @Given /^I should not be logged in$/
     */
    public function iShouldNotBeLoggedIn()
    {
        if ($this->getSecurityContext()->isGranted('ROLE_USER')) {
            throw new AuthenticationException('User is authenticated but shouldn\'t be.');
        }
    }

    /**
     * Spin method to wait for a specific condition to come true in the context
     *
     * @param \Closure $closure The function used to evaluate a condition
     * @param integer  $timeout The number of seconds to wait for the condition to become true
     *
     * @throws \RunTimeException When the condition does not become true withing $timeout seconds
     *
     * @return boolean
     */
    private function spin(\Closure $closure, $timeout = 15)
    {
        for ($i = 0; $i < $timeout; $i++) {
            try {
                if (true === $closure($this)) {
                    return true;
                }
            } catch (\Exception $e) { }
            sleep(1);
        }

        $backtrace = debug_backtrace();

        throw new \RuntimeException(
            sprintf(
                "Timeout thrown by %s::%s() on line %d",
                $backtrace[1]['class'],
                $backtrace[1]['function'],
                $backtrace[1]['line']
            )
        );
    }
}
