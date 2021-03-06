<?php

namespace Ulost\AnnonceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ulost\UserBundle\Entity\User;
use Ulost\ObjectBundle\Entity\Object;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Annonce
 *
 * @ORM\Table(name="annonce")
 * @ORM\Entity(repositoryClass="Ulost\AnnonceBundle\Repository\AnnonceRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Annonce
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
     * @ORM\Column(name="status", type="string", length=255)
     * @Assert\Choice({"lost", "found"})
     */
    private $status;

    /**
     *
     *  @ORM\ManyToOne(targetEntity="Ulost\UserBundle\Entity\User", inversedBy="annonces", cascade={"persist"})
     *  @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     *
     *  @ORM\ManyToOne(targetEntity="Ulost\ObjectBundle\Entity\Object", inversedBy="annonces", cascade={"persist"})
     *  @ORM\JoinColumn(nullable=false)
     */
    private $object;


    /**
     * @var string
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity="Ulost\AnnonceBundle\Entity\Reponse", mappedBy="annonce", cascade={"remove","persist"})
     **/
    private $reponses;

    /**
     * @var string
     *
     * @ORM\Column(name="remarque", type="text", length=255, nullable=true)
     *
     */
    private $remarque;



    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */

    private $updatedAt;


    /**
     * @ORM\Column(name="published", type="boolean", options={"default":0}))
     */
    private $published;

    /**
     *
     *  @ORM\ManyToOne(targetEntity="Ulost\VilleBundle\Entity\Ville", inversedBy="annonces")
     *  @ORM\JoinColumn(nullable=true)
     */
    private $ville;

    /**
     * @ORM\OneToOne(targetEntity="Ulost\MunicipaleBundle\Entity\Stock", mappedBy="annonce",  cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, name="stock_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $stock;

    /**
     * @ORM\OneToOne(targetEntity="Ulost\AnnonceBundle\Entity\ImageAnnonce", cascade={"persist", "remove"})
     * @Assert\Valid
     * @ORM\JoinColumn(nullable=true)
     */
    private $imageAnnonce;


    /**
     * @ORM\Column(name="archived", type="boolean", options={"default":0}))
     */
    private $archived;

    /**
     * @ORM\Column(name="archived_at", type="datetime", nullable=true)
     */

    private $archivedAt;

    /**
     * @ORM\ManyToOne(targetEntity="Ulost\AnnonceBundle\Entity\Annonce", inversedBy="enfants", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, name="parent_id", referencedColumnName="id")
     */
    private $parent;


    /**
     * @ORM\OneToMany(targetEntity="Ulost\AnnonceBundle\Entity\Annonce", mappedBy="parent", cascade={"persist", "remove"})
     */
    private $enfants;




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
     * Set status
     *
     * @param string $status
     *
     * @return Annonce
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }


    /**
     * Set user
     *
     * @param string $user
     *
     * @return Annonce
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }



    /**
     * Set object
     *
     * @param string $object
     *
     * @return Annonce
     */
    public function setObject(Object $object)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * Get object
     *
     * @return string
     */
    public function getObject()
    {
        return $this->object;
    }


    /**
     * Set date
     *
     * @param string $date
     *
     * @return Annonce
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set remarque
     *
     * @param string $remarque
     *
     * @return Annonce
     */
    public function setRemarque($remarque)
    {
        $this->remarque = $remarque;

        return $this;
    }

    /**
     * Get remarque
     *
     * @return string
     */
    public function getRemarque()
    {
        return $this->remarque;
    }

    /**




    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Annonce
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    /**
     * @ORM\PreUpdate
     */
    public function updateDate()

    {
        $this->setUpdatedAt(new \Datetime());
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
    public function getReponses()
    {
        return $this->reponses;
    }


    public function addReponse(Reponse $reponse)
    {
        $this->reponses[] = $reponse;
        $reponse->setAnnonce($this);
        return $this;
    }


    public function removeReponse(Reponse $reponse)
    {
        $this->reponses->removeElement($reponse);
    }

    public function __construct()
    {

        $this->object = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @param mixed $ville
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
    }




    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return Annonce
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set stock
     *
     * @param \Ulost\MunicipaleBundle\Entity\Stock $stock
     *
     * @return Annonce
     */
    public function setStock(\Ulost\MunicipaleBundle\Entity\Stock $stock = null)
    {
        $this->stock = $stock;
        $stock->setAnnonce($this);
        return $this;
    }

    /**
     * Get stock
     *
     * @return \Ulost\MunicipaleBundle\Entity\Stock
     */
    public function getStock()
    {
        return $this->stock;
    }

    public function getImageAnnonce()
    {
        return $this->imageAnnonce;
    }

    /**
     * @param mixed $image
     */
    public function setImageAnnonce($imageAnnonce)
    {
        $this->imageAnnonce = $imageAnnonce;
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
     * Set parent
     *
     * @param \Ulost\AnnonceBundle\Entity\Annonce $parent
     *
     * @return Annonce
     */
    public function setParent(\Ulost\AnnonceBundle\Entity\Annonce $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Ulost\AnnonceBundle\Entity\Annonce
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add enfant
     *
     * @param \Ulost\AnnonceBundle\Entity\Annonce $enfant
     *
     * @return Annonce
     */
    public function addEnfant(\Ulost\AnnonceBundle\Entity\Annonce $enfant)
    {
        $this->enfants[] = $enfant;
        $enfant->setParent($this);
        return $this;
    }

    /**
     * Remove enfant
     *
     * @param \Ulost\AnnonceBundle\Entity\Annonce $enfant
     */
    public function removeEnfant(\Ulost\AnnonceBundle\Entity\Annonce $enfant)
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

}
