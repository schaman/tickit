<?php

namespace Tickit\ProjectBundle\Entity;

/**
 * Interface for entities that must contain an attribute and a value
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
interface AttributeValueInterface
{
    /**
     * Gets the attribute object associated with this record
     *
     * @abstract
     * @return AttributeInterface
     */
    public function getAttribute();

    /**
     * Gets the value associated with this attribute-value pair
     *
     * @abstract
     * @return mixed
     */
    public function getValue();
}
