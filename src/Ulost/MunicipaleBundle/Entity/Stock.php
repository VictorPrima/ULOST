<?php

namespace Ulost\MunicipaleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ulost\AnnonceBundle\Entity\Annonce;

/**
 * Stock
 *
 * @ORM\Table(name="stock")
 * @ORM\Entity(repositoryClass="Ulost\MunicipaleBundle\Repository\StockRepository")
 */
class Stock
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
   * @ORM\OneToOne(targetEntity="Ulost\AnnonceBundle\Entity\Annonce",  inversedBy="stock", cascade={"persist", "remove"})
   * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
   */
    private $annonce;


    /**
     * @ORM\ManyToOne(targetEntity="Ulost\MunicipaleBundle\Entity\Emplacement", inversedBy="stocks", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $emplacement;

    /**
     * @ORM\ManyToOne(targetEntity="Ulost\MunicipaleBundle\Entity\Service", inversedBy="stocks", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $service;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDepot", type="datetime")
     */
    private $dateDepot;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateRetrait", type="datetime", nullable=true)
     */
    private $dateRetrait;


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
     * Set dateDepot
     *
     * @param \DateTime $dateDepot
     *
     * @return Stock
     */
    public function setDateDepot($dateDepot)
    {
        $this->dateDepot = $dateDepot;

        return $this;
    }

    /**
     * Get dateDepot
     *
     * @return \DateTime
     */
    public function getDateDepot()
    {
        return $this->dateDepot;
    }

    /**
     * Set dateRetrait
     *
     * @param \DateTime $dateRetrait
     *
     * @return Stock
     */
    public function setDateRetrait($dateRetrait)
    {
        $this->dateRetrait = $dateRetrait;

        return $this;
    }

    /**
     * Get dateRetrait
     *
     * @return \DateTime
     */
    public function getDateRetrait()
    {
        return $this->dateRetrait;
    }

    /**
     * Set annonce
     *
     * @param \Ulost\AnnonceBundle\Entity\Annonce $annonce
     *
     * @return Stock
     */
    public function setAnnonce(Annonce $annonce)
    {
        $this->annonce = $annonce;

        return $this;
    }

    /**
     * Get annonce
     *
     * @return \Ulost\AnnonceBundle\Entity\Annonce
     */
    public function getAnnonce()
    {

        return $this->annonce;
    }

    /**
     * Set emplacement
     *
     * @param \Ulost\MunicipaleBundle\Entity\Emplacement $emplacement
     *
     * @return Stock
     */
    public function setEmplacement(\Ulost\MunicipaleBundle\Entity\Emplacement $emplacement)
    {
        $this->emplacement = $emplacement;

        return $this;
    }

    /**
     * Get emplacement
     *
     * @return \Ulost\MunicipaleBundle\Entity\Emplacement
     */
    public function getEmplacement()
    {
        return $this->emplacement;
    }

    /**
     * Set service
     *
     * @param \Ulost\MunicipaleBundle\Entity\Service $service
     *
     * @return Stock
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
