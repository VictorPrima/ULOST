<?php

namespace Ulost\ObjectBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ulost\AnnonceBundle\Entity\Annonce;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Object
 *
 * @ORM\Table(name="object")
 * @ORM\Entity(repositoryClass="Ulost\ObjectBundle\Repository\ObjectRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Object
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Ulost\ObjectBundle\Entity\Category", inversedBy="objects", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;


    /**
     * @ORM\OneToOne(targetEntity="Ulost\ObjectBundle\Entity\Image", cascade={"persist"})
     * @Assert\Valid
     * @ORM\JoinColumn(nullable=true)
     */
    private $image;


    /**
     * @var string
     *
     * @ORM\Column(name="typeObjet", type="string", length=255)
     *
     */
    private $typeObjet;




    /**
     * @ORM\OneToMany(targetEntity="Ulost\ObjectBundle\Entity\Question", mappedBy="object", cascade={"persist", "remove"})
     */

    private $questions;

    /**
     * @ORM\OneToMany(targetEntity="Ulost\AnnonceBundle\Entity\Annonce", mappedBy="object", cascade={"persist"})
     */

    private $annonces;

    public function __construct()
    {

        $this->categorie = new ArrayCollection();
    }



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getTypeObjet()
    {
        return $this->typeObjet;
    }

    /**
     * @param string $typeObjet
     */
    public function setTypeObjet($typeObjet)
    {
        $this->typeObjet = $typeObjet;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;

    }


    /**
     * @return mixed
     */
    public function getQuestions()
    {
        return $this->questions;
    }


    /**
     * @param Question $question
     * @return $this
     */
    public function addQuestion(Question $question)
    {
        $this->questions[] = $question;
        $question->setObject($this);
        return $this;
    }


    /**
     * @param Question $question
     */
    public function removeQuestion(Question $question)
    {
        $this->questions->removeElement($question);
    }


    /**
     * @return mixed
     */
    public function getAnnonces()
    {
        return $this->annonces;
    }


    /**
     * @param Annonce $annonce
     * @return $this
     */
    public function addAnnonce(Annonce $annonce)
    {
        $this->annonces[] = $annonce;
        $annonce->setObject($this);
        return $this;
    }


    /**
     * @param Annonce $annonce
     */
    public function removeAnnonce(Annonce $annonce)
    {
        $this->annonces->removeElement($annonce);
    }
    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }







}
