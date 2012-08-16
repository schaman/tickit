<?php

namespace Tickit\ProjectBundle\Entity;

/**
 * Interface for entities that represent attribute in the application
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
interface AttributeInterface
{
    /**
     * Gets the name of this attribute
     *
     * @abstract
     * @return string
     */
    public function getName();

    /**
     * Gets the default value for this attribute
     *
     * @abstract
     * @return mixed
     */
    public function getDefaultValue();

}