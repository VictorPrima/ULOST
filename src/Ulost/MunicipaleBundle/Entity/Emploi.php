<?php
// src/MunicipaleBundle/Entity/Employe.php

namespace Ulost\MunicipaleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="emploi")
 */
class Emploi
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    /**

	* @ORM\ManyToOne(targetEntity="Ulost\UserBundle\Entity\User", inversedBy="emplois", cascade={"persist"})
	* @ORM\JoinColumn(nullable=false)
	*/
	private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Ulost\MunicipaleBundle\Entity\Service", inversedBy="emplois", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $service;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return Emploi
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set user
     *
     * @param \Ulost\UserBundle\Entity\User $user
     *
     * @return Emploi
     */
    public function setUser(\Ulost\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Ulost\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set service
     *
     * @param \Ulost\MunicipaleBundle\Entity\Service $service
     *
     * @return Emploi
     */
    public function setService(\Ulost\MunicipaleBundle\Entity\Service $service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \Ulost\MunicipaleBundle\Entity\Service
     */
    public function getService()
    {
        return $this->service;
    }
}
