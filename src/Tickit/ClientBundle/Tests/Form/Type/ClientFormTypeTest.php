<?php

namespace Tickit\ClientBundle\Tests\Form\Type;

use Tickit\ClientBundle\Entity\Client;
use Tickit\ClientBundle\Form\Type\ClientFormType;
use Tickit\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;

/**
 * ClientFormType tests
 *
 * @package Tickit\ClientBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientFormTypeTest extends AbstractFormTypeTestCase
{
    /**
     * The form type under test
     *
     * @var ClientFormType
     */
    private $type;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->formType = new ClientFormType();

        parent::setUp();
    }
    
    /**
     * Tests the form submit
     */
    public function testSubmitValidData()
    {
        $form = $this->factory->create($this->formType);

        $client = new Client();
        $client->setName('client name')
               ->setNotes('client notes')
               ->setUrl('http://client.com/home-page');

        $form->setData($client);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($client, $form->getData());

        $expectedViewComponents = ['name', 'url', 'notes'];
        $this->assertViewHasComponents($expectedViewComponents, $form->createView());
    }
}
