<?php

use Behat\Behat\Context\BehatContext;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Session;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
     * Hostname of the current session
     *
     * @var string
     */
    protected $hostname;

    /**
     * Constructor.
     *
     * @param array $parameters Context parameters (see app/config/behat.yml)
     */
    public function __construct(array $parameters)
    {
        $this->hostname = $parameters['hostname'];
        $driver = new Selenium2Driver('firefox', null);
        $this->session = new Session($driver);
        $this->session->start();
    }

    /**
     * @Given /^I am on "([^"]*)"$/
     */
    public function iAmOn($path)
    {
        $url = sprintf('http://%s%s', $this->hostname, $path);

        $this->session->visit($url);
        $this->session->wait(15000, 'typeof $ == "function"');
    }

    /**
     * @Given /^I should see a "([^"]*)" element$/
     */
    public function iShouldSeeAElement($tagName)
    {
        $success = $this->session->evaluateScript('$("' . $tagName . '").length > 0');

        return $success;
    }
}
