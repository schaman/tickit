<?php

namespace Tickit\ProjectBundle\Tests\Controller;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;

/**
 * TemplateController tests
 *
 * @package Tickit\ProjectBundle\Tests\Controller
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class TemplateControllerTest extends AbstractFunctionalTest
{
    /**
     * Tests the createProjectFormAction() method
     *
     * @return void
     */
    public function testCreateProjectFormActionServesCorrectMarkup()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $crawler = $client->request('get', $this->generateRoute('project_create_form'));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('input')->count());
    }

    /**
     * Tests the editProjectFormAction() method
     *
     * @return void
     */
    public function testEditProjectFormActionServesCorrectMarkup()
    {
        // todo
    }
}
