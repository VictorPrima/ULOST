<?php

namespace Ulost\VilleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * departement
 *
 * @ORM\Table(name="departement")
 * @ORM\Entity(repositoryClass="Ulost\VilleBundle\Repository\DepartementRepository")
 */
class Departement
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
     * @ORM\Column(name="code", type="string", length=4)
     */
    private $code;

    /**
     * @ORM\ManyToOne(targetEntity="Ulost\VilleBundle\Entity\Region", inversedBy="departements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Ulost\VilleBundle\Entity\Ville", mappedBy="departement")
     */
    private $villes;

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
     * Set code
     *
     * @param int $code
     *
     * @return departement
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set region
     *
     * @param integer $region
     *
     * @return departement
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return int
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return departement
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

    public function getVilles()
    {
        return $this->villes;
    }


    /**
     * @param ville $ville
     * @return $this
     */
    public function addVille(Ville $ville)
    {
        $this->villes[] = $ville;
        $ville->setDepartement($this);
        return $this;
    }


    /**
     * @param ville $ville
     */
    public function removeVille(Ville $ville)
    {
        $this->villes->removeElement($ville);
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->villes = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
