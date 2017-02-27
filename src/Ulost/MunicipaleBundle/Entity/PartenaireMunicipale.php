<?php
// src/MunicipaleBundle/Entity/PartenaireMunicipale.php

namespace Ulost\MunicipaleBundle\Entity;

use Ulost\UserBundle\Entity\User as User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="partenaire_municipale")
 */
class PartenaireMunicipale
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;	
	


    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

	
	/**

	* @ORM\ManyToOne(targetEntity="Ulost\UserBundle\Entity\User", cascade={"persist"})
	* @ORM\joinColumn(nullable=false)
	*/
	private $user;

	/**
     * @var string
     *
     * @ORM\Column(name="adresse", type="text", length=255)
     */
    private $adresse;
	
	 /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=64)
     */
    private $phone;

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
     * Set nom
     *
     * @param string $nom
     *
     * @return PartenaireMunicipale
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return PartenaireMunicipale
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return PartenaireMunicipale
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set user
     *
     * @param \Ulost\UserBundle\Entity\User $user
     *
     * @return PartenaireMunicipale
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
     * Add user
     *
     * @param \Ulost\UserBundle\Entity\User $user
     *
     * @return PartenaireMunicipale
     */
    public function addUser(\Ulost\UserBundle\Entity\User $user)
    {
        $this->user[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Ulost\UserBundle\Entity\User $user
     */
    public function removeUser(\Ulost\UserBundle\Entity\User $user)
    {
        $this->user->removeElement($user);
    }
}
