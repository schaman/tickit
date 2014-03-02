<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
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

namespace Tickit\Bundle\ProjectBundle\Form\Type\Picker;

use Tickit\Bundle\PickerBundle\Form\Type\Picker\DataTransformer\AbstractPickerDataTransformer;
use Tickit\Component\Entity\Manager\ProjectManager;

/**
 * Project picker data transformer.
 *
 * Responsible for transforming data representations for the project picker
 * field type.
 *
 * @package Namespace\Class
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectPickerDataTransformer extends AbstractPickerDataTransformer
{
    /**
     * The project manager
     *
     * @var ProjectManager
     */
    private $manager;

    /**
     * Constructor.
     *
     * @param ProjectManager $manager The project manager
     */
    public function __construct(ProjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     *  Transforms a given entity instance into a simple object.
     *
     * This allows the data transformer to encode it to a scalar
     *
     * @param mixed $entity The entity instance to transform
     *
     * @return string
     */
    protected function transformEntityToSimpleObject($entity)
    {
        $object = new \stdClass();
        $object->id = $entity->getId();
        $object->text = $entity->getName();

        return $object;
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
