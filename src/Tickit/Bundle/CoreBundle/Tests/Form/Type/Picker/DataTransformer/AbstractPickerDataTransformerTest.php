<?php

namespace Tickit\CoreBundle\Tests\Form\Type\Picker\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Tickit\CoreBundle\Form\Type\Picker\AbstractPickerType;
use Tickit\CoreBundle\Form\Type\Picker\DataTransformer\AbstractPickerDataTransformer;
use Tickit\CoreBundle\Tests\Form\Type\Picker\Mock\MockEntity;

/**
 * AbstractPickerDataTransformer tests
 *
 * @package Tickit\CoreBundle\Tests\Form\Type\Picker\DataTransformer
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AbstractPickerDataTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractPickerDataTransformer
     */
    private $sut;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->sut = $this->getMockForAbstractClass(
            '\Tickit\CoreBundle\Form\Type\Picker\DataTransformer\AbstractPickerDataTransformer'
        );
    }

    /**
     * Tests the setRestriction() method
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetRestrictionThrowsExceptionForInvalidRestriction()
    {
        $this->sut->setRestriction('invalid');
    }
    
    /**
     * Tests the transform() method
     */
    public function testTransformReturnsEmptyStringForNullValue()
    {
        $this->assertEquals('', $this->sut->transform(null));
    }

    /**
     * Tests the transform() method
     *
     * @expectedException \RuntimeException
     */
    public function testTransformThrowsExceptionForNonExistentIdentifierGetter()
    {
        $this->sut->expects($this->once())
                  ->method('getEntityIdentifier')
                  ->will($this->returnValue('invalid'));

        $this->sut->setRestriction(AbstractPickerType::RESTRICTION_SINGLE);

        $this->sut->transform(new MockEntity(1));
    }

    /**
     * Tests the transform() method
     */
    public function testTransformHandlesSingleEntityWithSingleSelectRestrictionEnabled()
    {
        $this->trainTransformerToReturnIdentifier();
        $this->sut->setRestriction(AbstractPickerType::RESTRICTION_SINGLE);

        $expectedData = '1';
        $this->assertEquals($expectedData, $this->sut->transform(new MockEntity(1)));
    }

    /**
     * Tests the transform() method
     */
    public function testTransformHandlesSingleEntityCorrectly()
    {
        $this->trainTransformerToReturnIdentifier();

        $collection = new ArrayCollection(
            [new MockEntity(1)]
        );

        $expectedData = '1';
        $this->assertEquals($expectedData, $this->sut->transform($collection));
    }

    /**
     * Tests the transform() method
     *
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     */
    public function testTransformThrowsExceptionForCollectionWithSingleRestrictionEnabled()
    {
        $this->sut->setRestriction(AbstractPickerType::RESTRICTION_SINGLE);

        $collection = new ArrayCollection(
            [new MockEntity(1), new MockEntity(2), new MockEntity(4)]
        );

        $this->sut->transform($collection);
    }

    /**
     * Tests the transform() method
     */
    public function testTransformHandlesEntityCollectionCorrectly()
    {
        $this->trainTransformerToReturnIdentifier(3);

        $collection = new ArrayCollection(
            [new MockEntity(1), new MockEntity(2), new MockEntity(4)]
        );

        $expectedData = '1,2,4';
        $this->assertEquals($expectedData, $this->sut->transform($collection));
    }

    /**
     * Tests the reverseTransform() method
     */
    public function testReverseTransformReturnsNullForEmptyString()
    {
        $this->assertNull($this->sut->reverseTransform(''));
    }

    /**
     * Tests the reverseTransform() method
     */
    public function testReverseTransformSkipsInvalidIdentifiers()
    {
        $this->sut->expects($this->exactly(2))
                  ->method('findEntityByIdentifier')
                  ->will($this->onConsecutiveCalls(new MockEntity(1), null));

        $this->sut->expects($this->at(0))
                  ->method('findEntityByIdentifier')
                  ->with(1);

        $this->sut->expects($this->at(1))
                  ->method('findEntityByIdentifier')
                  ->with(99);

        $expected = new ArrayCollection([new MockEntity(1)]);
        $this->assertEquals($expected, $this->sut->reverseTransform('1, 99'));
    }

    /**
     * Tests the reverseTransform() method
     */
    public function testReverseTransformHandlesSingleIdentifierCorrectly()
    {
        $this->sut->expects($this->once())
                  ->method('findEntityByIdentifier')
                  ->with(1)
                  ->will($this->returnValue(new MockEntity(1)));

        $expected = new ArrayCollection([new MockEntity(1)]);
        $this->assertEquals($expected, $this->sut->reverseTransform('1'));
    }

    /**
     * Tests the reverseTransform() method
     */
    public function testReverseTransformHandlesSingleIdentifierWithSingleRestrictionEnabledCorrectly()
    {
        $this->sut->setRestriction(AbstractPickerType::RESTRICTION_SINGLE);
        $this->sut->expects($this->once())
                  ->method('findEntityByIdentifier')
                  ->with(1)
                  ->will($this->returnValue(new MockEntity(1)));

        $expected = new MockEntity(1);
        $this->assertEquals($expected, $this->sut->reverseTransform('1'));
    }

    /**
     * Tests the reverseTransform() method
     */
    public function testReverseTransformHandlesMultipleIdentifiersCorrectly()
    {
        $this->sut->expects($this->exactly(2))
                  ->method('findEntityByIdentifier')
                  ->will($this->onConsecutiveCalls(new MockEntity(1), new MockEntity(2)));

        $this->sut->expects($this->at(0))
                  ->method('findEntityByIdentifier')
                  ->with(1);

        $this->sut->expects($this->at(1))
                  ->method('findEntityByIdentifier')
                  ->with(2);

        $expected = new ArrayCollection([new MockEntity(1), new MockEntity(2)]);
        $this->assertEquals($expected, $this->sut->reverseTransform('1, 2'));
    }

    /**
     * Tests the reverseTransform() method
     *
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     */
    public function testReverseTransformThrowsExceptionForMultipleIdentifiersWithSingleRestrictionEnabled()
    {
        $this->sut->setRestriction(AbstractPickerType::RESTRICTION_SINGLE);

        $this->sut->reverseTransform('1,3,4,5');
    }

    private function trainTransformerToReturnIdentifier($times = 1)
    {
        $this->sut->expects($this->exactly($times))
                  ->method('getEntityIdentifier')
                  ->will($this->returnValue('id'));
    }
}
