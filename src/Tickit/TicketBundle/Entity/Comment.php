<?php

namespace Tickit\TicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tickit\UserBundle\Entity\User;

/**
 * The Comment entity represents a comment that is placed on a Ticket by a given user
 *
 * @package Tickit\TicketBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="comments")
 */
class Comment
{
    /**
     * The unique identifier for the comment
     *
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The ticket that this comment belongs to
     *
     * @var Ticket
     * @ORM\ManyToOne(targetEntity="Ticket", inversedBy="comments")
     * @ORM\JoinColumn(name="ticket_id", referencedColumnName="id")
     */
    protected $ticket;

    /**
     * The user that created the comment
     *
     * @var User
     * @ORM\ManyToOne(targetEntity="Tickit\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * The message content on the comment
     *
     * @var string
     * @ORM\Column(type="string", length=1000)
     */
    protected $message;
}
