<?php

namespace Tickit\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * The User entity represents a logged in user in the application
 *
 * @ORM\Entity(repositoryClass="Tickit\UserBundle\Entity\Repository\UserRepository")
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{

    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /**
     * @ORM\Column(type="string", length=120)
     */
    protected $forename;

    /**
     * @ORM\Column(type="string", length=120)
     */
    protected $surname;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    protected $updated;

    /**
     * @ORM\ManyToMany(targetEntity="Group")
     * @ORM\JoinTable(name="users_groups",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @ORM\OneToMany(targetEntity="UserSession", mappedBy="user")
     */
    protected $sessions;

    /**
     * @ORM\OneToMany(targetEntity="Tickit\PermissionBundle\Entity\UserPermissionValue", mappedBy="user")
     */
    protected $permissions;

    /**
     * @ORM\Column(name="last_activity", type="datetime", nullable=true)
     */
    protected $lastActivity;


    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->sessions = new ArrayCollection();
        $this->permissions = new ArrayCollection();
        parent::__construct();
    }

    /**
     * Gets the ID for this user
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Updates the user's forename
     *
     * @param string $forename The new forename value
     */
    public function setForename($forename)
    {
        $this->forename = $forename;
    }

    /**
     * Updates the user's surname
     *
     * @param string $surname The new surname value
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * Updates the last activity time
     *
     * @param \DateTime $lastActivity
     */
    public function setLastActivity($lastActivity)
    {
        $this->lastActivity = $lastActivity;
    }

    /**
     * Gets the last activity time as a DateTime object
     *
     * @return \DateTime
     */
    public function getLastActivity()
    {
        return $this->lastActivity;
    }

    /**
     * Adds a session object to this user's collection of sessions
     *
     * @param UserSession $session
     */
    public function addSession(UserSession $session)
    {
        $this->sessions[] = $session;
    }

    /**
     * Returns the current session token
     *
     * @return array
     */
    public function getSessions()
    {
        return $this->sessions;
    }

    /**
     * Gets the user's concatenated forename and surname
     *
     * @return string
     */
    public function getFullName()
    {
        return sprintf('%s %s', $this->forename, $this->surname);
    }

    /**
     * Gets the time this user was updated as a DateTime object
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return new \DateTime($this->updated);
    }

    /**
     * Gets the created time as a DateTime object
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return new \DateTime($this->created);
    }

    /**
     *
     */
    public function getPrimaryGroup()
    {
        $groupNames = $this->getGroupNames();

        return array_shift($groupNames);
    }

}


