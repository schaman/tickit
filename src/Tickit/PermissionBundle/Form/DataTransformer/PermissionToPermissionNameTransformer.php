<?php

namespace Tickit\PermissionBundle\Form\DataTransformer;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Tickit\PermissionBundle\Entity\Permission;

/**
 * Permission to permission name transformer.
 *
 * Responsible for transforming permissions to permission names and vice versa.
 *
 * @package Tickit\PermissionBundle\Form\DataTransformer
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PermissionToPermissionNameTransformer implements DataTransformerInterface
{
    /**
     * The entity manager
     *
     * @var EntityManager
     */
    protected $em;

    /**
     * Constructor.
     *
     * @param EntityManager $em The entity manager
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms a value from the original representation to a transformed representation.
     *
     * @param mixed $value The value in the original representation
     *
     * @return mixed The value in the transformed representation
     *
     * @throws UnexpectedTypeException When the value is not an instance of Permission
     */
    public function transform($value)
    {
        if (!$value instanceof Permission) {
            throw new UnexpectedTypeException($value, 'Tickit\PermissionBundle\Entity\Permission');
        }

        return $value->getName();
    }

    /**
     * Transforms a value from the transformed representation to its original representation.
     *
     * @param mixed $value The value in the transformed representation
     *
     * @return mixed The value in the original representation
     *
     * @throws UnexpectedTypeException       When the argument is not a string (the name)
     * @throws TransformationFailedException When no Permissions is found for the given name
     */
    public function reverseTransform($value)
    {
        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $permission = $this->em
                           ->getRepository('TickitPermissionBundle:Permission')
                           ->findOneByName($value);

        if (!$permission instanceof Permission) {
            throw new TransformationFailedException(sprintf('No Permission found for name "%s"', $value));
        }

        return $permission;
    }
}
