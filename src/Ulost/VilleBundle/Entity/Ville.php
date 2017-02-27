<?php

namespace Ulost\VilleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Ulost\AnnonceBundle\Entity\Annonce;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;



/**
 * Ville
 *
 * @ORM\Table(name="ville")

 * @ORM\Entity(repositoryClass="Ulost\VilleBundle\Repository\VilleRepository")
 */
class Ville
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
     * @ORM\ManyToOne(targetEntity="Ulost\VilleBundle\Entity\Departement", inversedBy="villes")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $departement;

    /**
     * @ORM\OneToMany(targetEntity="Ulost\MunicipaleBundle\Entity\Service", mappedBy="ville")
     */
    private $services;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="cp", type="string", length=255)
     *
     */
    private $codePostal;

    /**
     * @ORM\OneToMany(targetEntity="Ulost\VilleBundle\Entity\VilleServiceRelation", mappedBy="ville", cascade={"persist"})
     */
    private $villeServiceRelations;

    /**
     * @ORM\OneToMany(targetEntity="Ulost\AnnonceBundle\Entity\Annonce", mappedBy="ville", cascade={"persist"})
     **/
    private $annonces;

    /**
     * @var string
     * @ORM\Column(name="lat", type="string", length=12)
     *
     */
    private $lat;

    /**
     * @var string
     * @ORM\Column(name="lon", type="string", length=12)
     *
     */
    private $lon;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->services = new \Doctrine\Common\Collections\ArrayCollection();
        $this->villeServiceRelations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->annonces = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Ville
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

    /**
     * Set codePostal
     *
     * @param string $codePostal
     *
     * @return Ville
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return string
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set lat
     *
     * @param string $lat
     *
     * @return Ville
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lon
     *
     * @param string $lon
     *
     * @return Ville
     */
    public function setLon($lon)
    {
        $this->lon = $lon;

        return $this;
    }

    /**
     * Get lon
     *
     * @return string
     */
    public function getLon()
    {
        return $this->lon;
    }

    /**
     * Set departement
     *
     * @param \Ulost\VilleBundle\Entity\Departement $departement
     *
     * @return Ville
     */
    public function setDepartement(\Ulost\VilleBundle\Entity\Departement $departement)
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * Get departement
     *
     * @return \Ulost\VilleBundle\Entity\Departement
     */
    public function getDepartement()
    {
        return $this->departement;
    }

    /**
     * Add service
     *
     * @param \Ulost\MunicipaleBundle\Entity\Service $service
     *
     * @return Ville
     */
    public function addService(\Ulost\MunicipaleBundle\Entity\Service $service)
    {
        $this->services[] = $service;

        return $this;
    }

    /**
     * Remove service
     *
     * @param \Ulost\MunicipaleBundle\Entity\Service $service
     */
    public function removeService(\Ulost\MunicipaleBundle\Entity\Service $service)
    {
        $this->services->removeElement($service);
    }

    /**
     * Get services
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Add villeServiceRelation
     *
     * @param \Ulost\VilleBundle\Entity\VilleServiceRelation $villeServiceRelation
     *
     * @return Ville
     */
    public function addVilleServiceRelation(\Ulost\VilleBundle\Entity\VilleServiceRelation $villeServiceRelation)
    {
        $this->villeServiceRelations[] = $villeServiceRelation;

        return $this;
    }

    /**
     * Remove villeServiceRelation
     *
     * @param \Ulost\VilleBundle\Entity\VilleServiceRelation $villeServiceRelation
     */
    public function removeVilleServiceRelation(\Ulost\VilleBundle\Entity\VilleServiceRelation $villeServiceRelation)
    {
        $this->villeServiceRelations->removeElement($villeServiceRelation);
    }

    /**
     * Get villeServiceRelations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVilleServiceRelations()
    {
        return $this->villeServiceRelations;
    }

    /**
     * Add annonce
     *
     * @param \Ulost\AnnonceBundle\Entity\Annonce $annonce
     *
     * @return Ville
     */
    public function addAnnonce(\Ulost\AnnonceBundle\Entity\Annonce $annonce)
    {
        $this->annonces[] = $annonce;

        return $this;
    }

    /**
     * Remove annonce
     *
     * @param \Ulost\AnnonceBundle\Entity\Annonce $annonce
     */
    public function removeAnnonce(\Ulost\AnnonceBundle\Entity\Annonce $annonce)
    {
        $this->annonces->removeElement($annonce);
    }

    /**
     * Get annonces
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnnonces()
    {
        return $this->annonces;
    }
}
