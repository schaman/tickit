<?php

namespace Tickit\ProjectBundle\Entity;

/**
 * Interface for entities that must contain an attribute and a value
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
interface AttributeValue
{
    public function getAttribute();

    public function getValue();
}
