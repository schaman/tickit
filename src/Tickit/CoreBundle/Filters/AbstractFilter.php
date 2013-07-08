<?php

namespace Tickit\CoreBundle\Filters;
use Doctrine\ORM\QueryBuilder;

/**
 * Abstract filter implementation.
 *
 * @package Tickit\CoreBundle\Filters
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractFilter implements QueryBuilderApplicableInterface
{
    /**
     * The value of this filter
     *
     * @var mixed
     */
    protected $value;

    /**
     * The filter key.
     *
     * Usually this is the name of the column that is being filtered
     *
     * @var string
     */
    protected $key;

    /**
     * Constructor
     *
     * @param string $key   The filter key
     * @param mixed  $value The filter value
     */
    public function __construct($key, $value)
    {
        $this->key   = $key;
        $this->value = $value;
    }

    /**
     * Gets the filter value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Gets the filter key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Returns the filter type
     *
     * @return string
     */
    abstract public function getType();

    /**
     * Returns true if the given filter key is valid on the provided query.
     *
     * If the filter key does not exist as a property on the root entity in
     * the query, then this method will return false.
     *
     * @param QueryBuilder $query The query builder to validate on
     * @param string       $key   The filter key to validate
     *
     * @return boolean
     */
    protected function filterKeyIsValidOnQuery(QueryBuilder $query, $key)
    {
        $entity = array_shift($query->getRootEntities());

        if (empty($entity)) {
            return false;
        }

        $classMeta = $query->getEntityManager()->getClassMetadata($entity);
        $reflection = new \ReflectionClass($classMeta->name);

        try {
            $reflection->getProperty($key);

            return true;
        } catch (\ReflectionException $e) {
            return false;
        }
    }
}
