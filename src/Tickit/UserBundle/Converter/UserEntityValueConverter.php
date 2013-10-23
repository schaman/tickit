<?php

namespace Tickit\UserBundle\Converter;

use Doctrine\ORM\EntityNotFoundException;
use Tickit\CoreBundle\Form\Type\Picker\EntityConverterInterface;
use Tickit\UserBundle\Decorator\UserEntityDisplayNameDecorator;
use Tickit\UserBundle\Manager\UserManager;

/**
 * Convert user entity values
 *
 * @package Tickit\UserBundle\Decorator
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class UserEntityValueConverter implements EntityConverterInterface
{
    /**
     * User manager
     *
     * @var \Tickit\UserBundle\Manager\UserManager
     */
    private $manager;

    /**
     * The user decorator
     *
     * @var UserEntityDisplayNameDecorator
     */
    private $decorator;

    /**
     * Constructor.
     *
     * @param UserManager                    $manager       The user manager
     * @param UserEntityDisplayNameDecorator $userDecorator The user decorator
     */
    public function __construct(UserManager $manager, UserEntityDisplayNameDecorator $userDecorator)
    {
        $this->manager = $manager;
        $this->decorator = $userDecorator;
    }

    /**
     * Convert user Id to a display name
     *
     * @param integer $userId User Id
     *
     * @return string
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function convert($userId)
    {
        $user = $this->manager->find($userId);
        if (!$user) {
            throw new EntityNotFoundException(sprintf('User not found (%d)', $userId));
        }

        return $this->decorator->decorate($user);
    }
}
