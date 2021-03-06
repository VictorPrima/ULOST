<?php
// src/MunicipaleBundle/Entity/Employe.php

namespace Ulost\MunicipaleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Ulost\MunicipaleBundle\Repository\EmplacementRepository")
 * @ORM\Table(name="emplacement")
 */
class Emplacement
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\ManyToOne(targetEntity="Ulost\MunicipaleBundle\Entity\Service", inversedBy="emplacements", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $service;

    /**
     * @ORM\ManyToOne(targetEntity="Ulost\MunicipaleBundle\Entity\Emplacement", inversedBy="enfants", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, name="parent_id", referencedColumnName="id")
     */
    private $parent;


    /**
     * @ORM\OneToMany(targetEntity="Ulost\MunicipaleBundle\Entity\Emplacement", mappedBy="parent", cascade={"persist", "remove"})
     */
    private $enfants;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */

    private $type;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */

    private $name;


    /**
     * @ORM\OneToMany(targetEntity="Ulost\MunicipaleBundle\Entity\Stock", mappedBy="emplacement", cascade={"persist"})
     */
    private $stocks;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->enfants = new \Doctrine\Common\Collections\ArrayCollection();
        $this->stocks = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set type
     *
     * @param string $type
     *
     * @return Emplacement
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set service
     *
     * @param \Ulost\MunicipaleBundle\Entity\Service $service
     *
     * @return Emplacement
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

    /**
     * Set parent
     *
     * @param \Ulost\MunicipaleBundle\Entity\Emplacement $parent
     *
     * @return Emplacement
     */
    public function setParent(\Ulost\MunicipaleBundle\Entity\Emplacement $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Ulost\MunicipaleBundle\Entity\Emplacement
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add enfant
     *
     * @param \Ulost\MunicipaleBundle\Entity\Emplacement $enfant
     *
     * @return Emplacement
     */
    public function addEnfant(\Ulost\MunicipaleBundle\Entity\Emplacement $enfant)
    {
        $this->enfants[] = $enfant;
        $enfant->setParent($this);
        return $this;
    }

    /**
     * Remove enfant
     *
     * @param \Ulost\MunicipaleBundle\Entity\Emplacement $enfant
     */
    public function removeEnfant(\Ulost\MunicipaleBundle\Entity\Emplacement $enfant)
    {
        $this->enfants->removeElement($enfant);
    }

    /**
     * Get enfants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEnfants()
    {
        return $this->enfants;
    }

    /**
     * Add stock
     *
     * @param \Ulost\MunicipaleBundle\Entity\Stock $stock
     *
     * @return Emplacement
     */
    public function addStock(\Ulost\MunicipaleBundle\Entity\Stock $stock)
    {
        $this->stocks[] = $stock;
        $stock->setEmplacement($this);
        return $this;
    }

    /**
     * Remove stock
     *
     * @param \Ulost\MunicipaleBundle\Entity\Stock $stock
     */
    public function removeStock(\Ulost\MunicipaleBundle\Entity\Stock $stock)
    {
        $this->stocks->removeElement($stock);
    }

    /**
     * Get stocks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStocks()
    {
        return $this->stocks;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Emplacement
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
