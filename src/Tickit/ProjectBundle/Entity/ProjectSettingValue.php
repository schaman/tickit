<?php

namespace Tickit\ProjectBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * The ProjectSettingValue entity represents a project's value against a specific ProjectSetting
 *
 * @package Tickit\ProjectBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="project_setting_values")
 */
class ProjectSettingValue
{
    /**
     * The project that this setting value is associated with
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project;

    /**
     * The setting that this value is for
     *
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="ProjectSetting")
     * @ORM\JoinColumn(name="project_setting_id", referencedColumnName="id")
     */
    protected $setting;

    /**
     * The setting value
     *
     * @ORM\Column(type="string", length=120)
     */
    protected $value;

    /**
     * Gets the associated setting object
     *
     * @return ProjectSetting
     */
    public function getSetting()
    {
        return $this->setting;
    }

    /**
     * Gets the attribute value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
