<?php

use Behat\Behat\Context\BehatContext;
use Behat\Behat\Exception\PendingException;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Session;

/**
 * Features context.
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 * @author Mark Wilson   <mark@89allport.co.uk>
 */
class FeatureContext extends BehatContext
{
    use Behat\Symfony2Extension\Context\KernelDictionary;

    /**
     * Mink session
     *
     * @var Session
     */
    protected $session;

    /**
     * Constructor.
     *
     * @param array $parameters Context parameters (see app/config/behat.yml)
     */
    public function __construct(array $parameters)
    {
        $driver = new Selenium2Driver('firefox', null, 'http://tickit.local');
        $this->session = new Session($driver);
        $this->session->start();
    }

    /**
     * @Given /^I am on "([^"]*)"$/
     */
    public function iAmOn($url)
    {
        $this->session->visit($url);
    }

    /**
     * @Then /^the response code should be "([^"]*)"$/
     */
    public function theResponseCodeShouldBe($responseCode)
    {
        // todo: assert
    }

    /**
     * @Given /^I should see a "([^"]*)" element$/
     */
    public function iShouldSeeAElement($arg1)
    {
        throw new PendingException();
    }
}
