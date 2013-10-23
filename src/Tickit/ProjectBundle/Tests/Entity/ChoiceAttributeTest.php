<?php

namespace Tickit\ProjectBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Tickit\ProjectBundle\Entity\ChoiceAttribute;
use Tickit\ProjectBundle\Entity\ChoiceAttributeChoice;

/**
 * ChoiceAttribute tests
 *
 * @package Tickit\ProjectBundle\Tests\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ChoiceAttributeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The attribute under test
     *
     * @var ChoiceAttribute
     */
    private $attribute;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->attribute = new ChoiceAttribute();
    }
    
    /**
     * Tests the getChoicesAsArray() method
     */
    public function testGetChoicesAsArrayReturnsCorrectArray()
    {
        $choice1 = new ChoiceAttributeChoice();
        $choice1->setName('name1')
                ->setId(1);

        $choice2 = clone $choice1;
        $choice2->setName('name2')
                ->setId(2);

        $choices = new ArrayCollection(array($choice1, $choice2));

        $this->attribute->setChoices($choices);

        $expected = array(
            1 => 'name1',
            2 => 'name2'
        );

        $this->assertEquals($expected, $this->attribute->getChoicesAsArray());
    }
}
