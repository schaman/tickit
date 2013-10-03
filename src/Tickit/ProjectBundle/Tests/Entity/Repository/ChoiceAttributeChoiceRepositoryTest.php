<?php

namespace Tickit\ProjectBundle\Tests\Entity\Repository;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Tests\OrmTestCase;
use Tickit\CoreBundle\Tests\AbstractOrmTest;
use Tickit\ProjectBundle\Entity\ChoiceAttribute;
use Tickit\ProjectBundle\Entity\ChoiceAttributeChoice;
use Tickit\ProjectBundle\Entity\Repository\ChoiceAttributeChoiceRepository;

/**
 * ChoiceAttributeChoiceRepositoryTest tests
 *
 * @package Tickit\ProjectBundle\Tests\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ChoiceAttributeChoiceRepositoryTest extends AbstractOrmTest
{
    /**
     * The repo under test
     *
     * @var ChoiceAttributeChoiceRepository
     */
    private $repo;

    /**
     * @return ChoiceAttributeChoiceRepository
     */
    protected function setUp()
    {
        $em = $this->getEntityManager(
            'Tickit\\ProjectBundle\\Entity',
            array('TickitProjectBundle' => 'Tickit\\ProjectBundle\\Entity')
        );

        $this->repo = $em->getRepository('TickitProjectBundle:ChoiceAttributeChoice');
    }

    /**
     * Tests the getFindAllForAttributeQueryBuilder() method
     */
    public function testGetFindAllForAttributeQueryBuilder()
    {
        $attribute = new ChoiceAttribute();
        $attribute->setId(1);

        $queryBuilder = $this->repo->getFindAllForAttributeQueryBuilder($attribute);

        $from = $queryBuilder->getDQLPart('from');
        $this->assertNotEmpty($from);

        $fromPart = $from[0];
        $this->assertEquals('TickitProjectBundle:ChoiceAttributeChoice', $fromPart->getFrom());
        $this->assertEquals('c.attribute = :attribute', (string) $queryBuilder->getDQLPart('where'));
        $this->assertEquals(1, $queryBuilder->getParameter('attribute')->getValue());
    }
}
