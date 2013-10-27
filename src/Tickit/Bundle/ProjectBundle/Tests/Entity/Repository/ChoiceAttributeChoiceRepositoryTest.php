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

namespace Tickit\ProjectBundle\Tests\Entity\Repository;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Tests\OrmTestCase;
use Tickit\Bundle\CoreBundle\Tests\AbstractOrmTest;
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
