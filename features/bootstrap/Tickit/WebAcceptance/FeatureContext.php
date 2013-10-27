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

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Features context.
 *
 * @package Tickit\WebAcceptance
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @author  Mark Wilson   <mark@89allport.co.uk>
 */
class FeatureContext extends RawMinkContext implements KernelAwareInterface
{
    /**
     * The application kernel
     *
     * @var KernelInterface
     */
    private $kernel;

    /**
     * Context parameters
     *
     * @var array
     */
    private $parameters = array();

    /**
     * Constructor.
     *
     * @param array $parameters Context parameters (see app/config/behat.yml)
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
        $this->useContext('web-user', new WebUserContext());
        $this->useContext('data', new DataContext());
    }

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
     * Returns Container instance.
     *
     * @return ContainerInterface
     */
    private function getContainer()
    {
        return $this->kernel->getContainer();
    }

    /**
     * Returns a container parameter value.
     *
     * @param string $name The parameter name to fetch
     *
     * @return mixed
     */
    private function getContainerParameter($name)
    {
        return $this->getContainer()->getParameter($name);
    }
}
