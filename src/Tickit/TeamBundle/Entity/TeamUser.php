<?php

namespace Tickit\TeamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * The TeamUser entity represents a user's affiliation with a team in the application
 *
 * @todo This needs to be reworked so that a doctrine many-to-many table is used for teams --> users
 *
 * @ORM\Entity
 * @ORM\Table(name="team_users")
 */
class TeamUser
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Tickit\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Tickit\TeamBundle\Entity\Team")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     */
    protected $team;


    /**
     * Sets the user
     *
     * @param \Tickit\UserBundle\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Gets the user
     *
     * @return \Tickit\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Sets the team
     *
     * @param \Tickit\TeamBundle\Entity\Team $team
     */
    public function setTeam(Team $team)
    {
        $this->team = $team;
    }

    /**
     * Gets the team
     *
     * @return \Tickit\TeamBundle\Entity\Team
     */
    public function getTeam()
    {
        return $this->team;
    }
}
