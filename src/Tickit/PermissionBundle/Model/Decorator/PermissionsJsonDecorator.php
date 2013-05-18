<?php

namespace Tickit\PermissionBundle\Model\Decorator;

use Tickit\CoreBundle\Decorator\AbstractJsonDecorator;
use Tickit\PermissionBundle\Model\Permission;

/**
 * Permissions JSON decorator.
 *
 * Decorates Permission models as JSON output.
 *
 * @package Tickit\PermissionBundle\Model\Decorator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @see     Tickit\PermissionBundle\Model\Permssion
 */
class PermissionsJsonDecorator extends AbstractJsonDecorator
{
    /**
     * Parses data on the current decorator into a format ready for the renderer
     *
     * @throws \InvalidArgumentException If the current data is not of a valid type
     *
     * @return mixed
     */
    protected function parseData()
    {
        $data = $this->data;
        if (!is_array($data) || empty($data)) {
            throw new \InvalidArgumentException('The decorator expects an array');
        }

        $flattenedData = array();
        /** @var Permission $permission */
        foreach ($data as $permission) {

            if (!$permission instanceof Permission) {
                throw new \InvalidArgumentException(
                    'The decorator expects an array of Tickit\PermissionBundle\Model\Permission'
                );
            }

            $permissionId = $permission->getId();
            $flattenedData[$permissionId]['name'] = $permission->getName();
            $flattenedData[$permissionId]['values']['group'] = $permission->getGroupValue();
            $flattenedData[$permissionId]['values']['user'] = $permission->getUserValue();
        }

        return $flattenedData;
    }
}