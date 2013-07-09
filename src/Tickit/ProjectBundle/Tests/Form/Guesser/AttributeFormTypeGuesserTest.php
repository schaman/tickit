<?php

namespace Tickit\ProjectBundle\Tests\Form\Guesser;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\ProjectBundle\Entity\AbstractAttribute;
use Tickit\ProjectBundle\Form\Guesser\AttributeFormTypeGuesser;

/**
 * AttributeFormTypeGuesser tests
 *
 * @package Tickit\ProjectBundle\Tests\Form\Guesser
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AttributeFormTypeGuesserTest extends AbstractFunctionalTest
{
    /**
     * Tests the guessByAttributeType() method
     *
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function testGuessByAttributeTypeThrowsExceptionForInvalidAttributeType()
    {
        $guesser = $this->getGuesser();

        $guesser->guessByAttributeType('something not valid');
    }

    /**
     * Tests the guessByAttributeType() method
     *
     * @return void
     */
    public function testGuessByAttributeTypeReturnsCorrectTypeForLiteralAttribute()
    {
        $guesser = $this->getGuesser();
        $formType = $guesser->guessByAttributeType(AbstractAttribute::TYPE_LITERAL);

        $this->assertInstanceOf('Tickit\ProjectBundle\Form\Type\LiteralAttributeFormType', $formType);
    }

    /**
     * Tests the guessByAttributeType() method
     *
     * @return void
     */
    public function testGuessByAttributeTypeReturnsCorrectTypeForEntityAttribute()
    {
        $guesser = $this->getGuesser();
        $formType = $guesser->guessByAttributeType(AbstractAttribute::TYPE_ENTITY);

        $this->assertInstanceOf('Tickit\ProjectBundle\Form\Type\EntityAttributeFormType', $formType);
    }

    /**
     * Tests the guessByAttributeType() method
     *
     * @return void
     */
    public function testGuessByAttributeTypeReturnsCorrectTypeForChoiceAttribute()
    {
        $guesser = $this->getGuesser();
        $formType = $guesser->guessByAttributeType(AbstractAttribute::TYPE_CHOICE);

        $this->assertInstanceOf('Tickit\ProjectBundle\Form\Type\ChoiceAttributeFormType', $formType);
    }

    /**
     * Gets a guesser instance
     *
     * @return AttributeFormTypeGuesser
     */
    private function getGuesser()
    {
        return $this->createClient()->getContainer()->get('tickit_project.attribute_form_type_guesser');
    }
}
