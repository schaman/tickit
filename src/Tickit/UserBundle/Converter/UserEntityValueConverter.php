<?php

namespace Tickit\UserBundle\Converter;

use Doctrine\ORM\EntityNotFoundException;
use Tickit\UserBundle\Decorator\UserEntityDisplayNameDecorator;
use Tickit\UserBundle\Manager\UserManager;

/**
 * Convert user entity values
 *
 * @package Tickit\UserBundle\Decorator
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class UserEntityValueConverter
{
    /**
     * User manager
     *
     * @var \Tickit\UserBundle\Manager\UserManager
     */
    private $manager;

    /**
     * Constructor.
     *
     * @param UserManager $manager
     */
    public function __construct(UserManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Convert user Id to a display name
     *
     * @param integer $userId User Id
     *
     * @return string
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function convertUserIdToDisplayName($userId)
    {
        $user = $this->manager->find($userId);
        if (!$user) {
            throw new EntityNotFoundException(sprintf('User not found (%d)', $userId));
        }

        $decorator = new UserEntityDisplayNameDecorator();

        return $decorator->decorate($user);
    }
}
