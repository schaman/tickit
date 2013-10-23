<?php

namespace Tickit\CoreBundle\Decorator\Collection;

use Tickit\CoreBundle\Decorator\DomainObjectDecoratorInterface;

/**
 * Domain object collection array decorator.
 *
 * Responsible for decorating a collection of domain objects as arrays
 *
 * @package Tickit\CoreBundle\Decorator\Collection
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class DomainObjectCollectionDecorator implements DomainObjectCollectionDecoratorInterface
{
    /**
     * A domain object decorator
     *
     * @var DomainObjectDecoratorInterface
     */
    private $decorator;

    /**
     * Constructor.
     *
     * @param DomainObjectDecoratorInterface $decorator A domain object decorator
     */
    public function __construct(DomainObjectDecoratorInterface $decorator)
    {
        $this->decorator = $decorator;
    }

    /**
     * Decorates a collection of domain objects and returns the result.
     *
     * @param array $data             The domain objects to be decorated
     * @param array $propertyNames    Property names of the domain objects to include in the decorated result
     * @param array $staticProperties The static properties to attach to each decorated result
     *
     * @return array
     */
    public function decorate(array $data, array $propertyNames, array $staticProperties = array())
    {
        $decorated = [];

        foreach ($data as $object) {
            $decorated[] = $this->decorator->decorate($object, $propertyNames, $staticProperties);
        }

        return $decorated;
    }
}
