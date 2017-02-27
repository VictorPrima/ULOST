<?php

namespace Ulost\VilleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * region
 *
 * @ORM\Table(name="region")
 * @ORM\Entity(repositoryClass="Ulost\VilleBundle\Repository\RegionRepository")
 */
class Region
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Ulost\VilleBundle\Entity\Departement", mappedBy="region")
     */
    private $departements;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return region
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

    public function getDepartements()
    {
        return $this->departements;
    }


    /**
     * @param departement $departement
     * @return $this
     */
    public function addDepartement(Departement $departement)
    {
        $this->departements[] = $departement;
        $departement->setRegion($this);
        return $this;
    }


    /**
     * @param departement $departement
     */
    public function removeDepartement(Departement $departement)
    {
        $this->departements->removeElement($departement);
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->departements = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
