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

namespace Tickit\Bundle\UserBundle\Form\Type\Picker\DataTransformer;

use Tickit\Bundle\CoreBundle\Form\Type\Picker\DataTransformer\AbstractPickerDataTransformer;
use Tickit\Component\Entity\Manager\UserManager;

/**
 * User Picker data transformer.
 *
 * @package Tickit\Bundle\UserBundle\Form\Type\Picker\DataTransformer
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserPickerDataTransformer extends AbstractPickerDataTransformer
{
    /**
     * The user manager
     *
     * @var UserManager
     */
    private $manager;

    /**
     * Constructor.
     *
     * @param UserManager $manager The user manager
     */
    public function __construct(UserManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Returns the name of the entity identifier.
     *
     * @return string
     */
    protected function getEntityIdentifier()
    {
        return 'id';
    }

    /**
     * Returns an entity instance by identifier
     *
     * @param mixed $identifier The entity identifier value
     *
     * @return mixed
     */
    protected function findEntityByIdentifier($identifier)
    {
        return $this->manager->find($identifier);
    }
}
