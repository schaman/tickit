<?php

/*
 * Tickit, an open source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tickit\WebAcceptance;

use Behat\Mink\Element\NodeElement;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Tickit\Component\Model\User\User;
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
     * The currently logged in user
     *
     * @var User
     */
    public $loggedInUser;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->useContext('data', new DataContext());
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
        $this->getSession()->wait(2500);
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
     * @Then /^I should wait and see (\d+) table rows$/
     */
    public function iShouldWaitAndSeeTableRows($number)
    {
        $this->getSession()->wait(1500);
        $this->assertNumElements($number, 'div.main table tbody tr');
    }

    /**
     * @Given /^I am a logged in user$/
     */
    public function iAmALoggedInUser()
    {
        $this->iAmLoggedInWithRole('ROLE_USER');
    }

    /**
     * @Given /^I am a logged in admin$/
     */
    public function iAmALoggedInAdmin()
    {
        $this->iAmLoggedInWithRole('ROLE_ADMIN');
    }

    /**
     * @Given /^I am a logged in super admin/
     */
    public function iAmALoggedInSuperAdmin()
    {
        $this->iAmLoggedInWithRole('ROLE_SUPER_ADMIN');
    }

    /**
     * @Then /^I should not be logged in$/
     */
    public function iShouldNotBeLoggedIn()
    {
        if ($this->getSecurityContext()->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw new AuthenticationException('User is authenticated but shouldn\'t be.');
        }
    }

    /**
     * @Then /^I should be logged in$/
     */
    public function iShouldBeLoggedIn()
    {
        if ($this->getSecurityContext()->isGranted('IS_AUTHENTICATED_REMEMBERED') === false) {
            throw new AuthenticationException('User is not authenticated but should be.');
        }
    }

    /**
     * @When /^I type "([^"]*)", wait and select "([^"]*)" from picker "([^"]*)"$/
     */
    public function iTypeWaitAndSelectFromPicker($typedValue, $valueToSelect, $pickerId)
    {
        $session = $this->getSession();
        $page = $session->getPage();

        $select2Id = sprintf('#s2id_%s input[type="text"]', $pickerId);
        $select2 = $page->find('css', $select2Id);
        $select2->setValue($typedValue);
        $this->iWait();

        $results = $page->findAll('css', 'li.select2-result');
        /** @var NodeElement $result */
        foreach ($results as $result) {
            if ($result->getText() == $valueToSelect) {
                $result->click();
                break;
            }
        }
    }

    /**
     * @Then /^I should see (\d+) pages$/
     */
    public function iShouldSeePages($numberOfPages)
    {
        // we add 2 to the expected page count, because there will be both "next"
        // and "prev" <li> control elements
        $this->assertNumElements($numberOfPages + 2, 'div.list-pagination ul.pagination li a');
    }

    /**
     * @When /^I click page (\d+)$/
     */
    public function iClickPage($pageNumber)
    {
        $page = $this->getSession()->getPage();
        $button = $page->find('css', sprintf('div.list-pagination ul.pagination li a[data-page="%d"]', $pageNumber));
        $button->click();
    }

    /**
     * @Given /^I refresh the listing$/
     */
    public function iRefreshTheListing()
    {
        $page = $this->getSession()->getPage();
        $button = $page->find('css', 'div.refresh a');
        $button->click();
    }

    /**
     * Creates a user and logs in
     *
     * @param string|array $role         The role(s) for the user
     * @param string       $emailAddress The email address of the user
     */
    private function iAmLoggedInWithRole($role, $emailAddress  = 'hello@tickit.io')
    {
        /** @var DataContext $dataContext */
        $dataContext = $this->getSubcontext('data');
        $this->loggedInUser = $dataContext->createUser($emailAddress, $emailAddress, 'password', $role);

        $this->visit($this->generateUrl('fos_user_security_login'));
        $this->fillField('_username', $emailAddress);
        $this->fillField('_password', 'password');
        $this->pressButton('Login');
        $this->getSession()->wait(1000);
    }

    /**
     * Generates a returns a page URL
     *
     * @param string $routeName  The route name of the URL to generate
     * @param array  $parameters An array of route parameters
     *
     * @return string
     */
    private function generateUrl($routeName, array $parameters = array())
    {
        $router = $this->getService('router');
        $path = $router->generate($routeName, $parameters);

        if (true === $this->isUsingSelenium2Driver()) {
            return sprintf('%s%s', $this->getMinkParameter('base_url'), $path);
        }

        return $path;
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

    /**
     * @Given /^I wait$/
     */
    private function iWait()
    {
        $this->getSession()->wait(1000);
    }

    /**
     * Returns true if the current driver is Selenium2
     *
     * @return boolean
     */
    private function isUsingSelenium2Driver()
    {
        return ('Selenium2Driver' === strstr(get_class($this->getSession()->getDriver()), 'Selenium2Driver'));
    }
}
