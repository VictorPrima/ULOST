<?php

namespace Ulost\CorrespondanceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ulost\UserBundle\Entity\User;
use Ulost\ObjectBundle\Entity\Object;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Correspondance
 *
 * @ORM\Table(name="correspondance")
 * @ORM\Entity(repositoryClass="Ulost\CorrespondanceBundle\Repository\CorrespondanceRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Correspondance
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
     * @ORM\ManyToMany(targetEntity="Ulost\AnnonceBundle\Entity\Annonce", orphanRemoval=true)
     * @ORM\JoinTable(name="correspondance_annonce_found",
     *     joinColumns={@ORM\JoinColumn(name="correspondance_id", referencedColumnName="id", onDelete="cascade")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="annonce_id", referencedColumnName="id", onDelete="cascade")}
     * )
     */
    private $found;

    /**
     * @ORM\ManyToMany(targetEntity="Ulost\AnnonceBundle\Entity\Annonce", orphanRemoval=true)
     * @ORM\JoinTable(name="correspondance_annonce_lost",
     *     joinColumns={@ORM\JoinColumn(name="correspondance_id", referencedColumnName="id", onDelete="cascade")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="annonce_id", referencedColumnName="id", onDelete="cascade")}
     * )
     */
    private $lost;

    /**
     * @ORM\Column(name="confirmed", type="boolean", options={"default":0}))
     */
    private $confirmed;

    /**
     * @ORM\Column(name="date", type="datetime")
     */

    private $date;


    /**
     * @ORM\Column(name="archived", type="boolean", options={"default":0}))
     */
    private $archived;

    /**
     * @ORM\Column(name="archived_at", type="datetime", nullable=true)
     */

    private $archivedAt;


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
     * Set confirmed
     *
     * @param boolean $confirmed
     *
     * @return Correspondance
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    /**
     * Get confirmed
     *
     * @return boolean
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * Set found
     *
     * @param \Ulost\AnnonceBundle\Entity\Annonce $found
     *
     * @return Correspondance
     */
    public function setFound(\Ulost\AnnonceBundle\Entity\Annonce $found = null)
    {
        $this->found = $found;

        return $this;
    }

    /**
     * Get found
     *
     * @return \Ulost\AnnonceBundle\Entity\Annonce
     */
    public function getFound()
    {
        return $this->found;
    }

    /**
     * Set lost
     *
     * @param \Ulost\AnnonceBundle\Entity\Annonce $lost
     *
     * @return Correspondance
     */
    public function setLost(\Ulost\AnnonceBundle\Entity\Annonce $lost = null)
    {
        $this->lost = $lost;

        return $this;
    }


    /**
     * @return ArrayCollection
     */
    public function getLost()
    {
        return $this->lost;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->found = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lost = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add found
     *
     * @param \Ulost\AnnonceBundle\Entity\Annonce $found
     *
     * @return Correspondance
     */
    public function addFound(\Ulost\AnnonceBundle\Entity\Annonce $found)
    {
        $this->found[] = $found;

        return $this;
    }

    /**
     * Remove found
     *
     * @param \Ulost\AnnonceBundle\Entity\Annonce $found
     */
    public function removeFound(\Ulost\AnnonceBundle\Entity\Annonce $found)
    {
        $this->found->removeElement($found);
    }

    /**
     * Add lost
     *
     * @param \Ulost\AnnonceBundle\Entity\Annonce $lost
     *
     * @return Correspondance
     */
    public function addLost(\Ulost\AnnonceBundle\Entity\Annonce $lost)
    {
        $this->lost[] = $lost;

        return $this;
    }

    /**
     * Remove lost
     *
     * @param \Ulost\AnnonceBundle\Entity\Annonce $lost
     */
    public function removeLost(\Ulost\AnnonceBundle\Entity\Annonce $lost)
    {
        $this->lost->removeElement($lost);
    }

    /**
     * @return mixed
     */
    public function getArchived()
    {
        return $this->archived;
    }

    /**
     * @param mixed $archived
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;
    }

    /**
     * @return mixed
     */
    public function getArchivedAt()
    {
        return $this->archivedAt;
    }

    /**
     * @param mixed $archivedAt
     */
    public function setArchivedAt($archivedAt)
    {
        $this->archivedAt = $archivedAt;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }




}
