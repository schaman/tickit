<?php

namespace Tickit\UserBundle\Form\Type\Picker\DataTransformer;

use Tickit\Bundle\CoreBundle\Form\Type\Picker\DataTransformer\AbstractPickerDataTransformer;
use Tickit\UserBundle\Manager\UserManager;

/**
 * User Picker data transformer.
 *
 * @package Tickit\UserBundle\Form\Type\Picker\DataTransformer
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
