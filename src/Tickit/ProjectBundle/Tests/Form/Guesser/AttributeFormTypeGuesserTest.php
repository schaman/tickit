<?php

namespace Tickit\ProjectBundle\Tests\Form\Guesser;

use Tickit\CoreBundle\Tests\AbstractUnitTest;
use Tickit\ProjectBundle\Entity\AbstractAttribute;
use Tickit\ProjectBundle\Form\Guesser\AttributeFormTypeGuesser;

/**
 * AttributeFormTypeGuesser tests
 *
 * @package Tickit\ProjectBundle\Tests\Form\Guesser
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AttributeFormTypeGuesserTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $entityAttributeFormType;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->entityAttributeFormType = $this->getMockBuilder('\Tickit\ProjectBundle\Form\Type\EntityAttributeFormType')
                                              ->disableOriginalConstructor()
                                              ->getMock();
    }

    /**
     * Tests the guessByAttributeType() method
     *
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function testGuessByAttributeTypeThrowsExceptionForInvalidAttributeType()
    {
        $this->getGuesser()->guessByAttributeType('something not valid');
    }

    /**
     * Tests the guessByAttributeType() method
     *
     * @return void
     */
    public function testGuessByAttributeTypeReturnsCorrectTypeForLiteralAttribute()
    {
        $formType = $this->getGuesser()->guessByAttributeType(AbstractAttribute::TYPE_LITERAL);

        $this->assertInstanceOf('Tickit\ProjectBundle\Form\Type\LiteralAttributeFormType', $formType);
    }

    /**
     * Tests the guessByAttributeType() method
     *
     * @return void
     */
    public function testGuessByAttributeTypeReturnsCorrectTypeForEntityAttribute()
    {
        $formType = $this->getGuesser()->guessByAttributeType(AbstractAttribute::TYPE_ENTITY);

        $this->assertSame($this->entityAttributeFormType, $formType);
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
        return new AttributeFormTypeGuesser($this->entityAttributeFormType);
    }
}
