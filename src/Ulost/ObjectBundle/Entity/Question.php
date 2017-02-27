<?php

namespace Ulost\ObjectBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Tests\StringableObject;
use Symfony\Component\Validator\Constraints as Assert;
use Ulost\AnnonceBundle\Entity\Reponse;


/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="Ulost\ObjectBundle\Repository\QuestionRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Question
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
     * @ORM\ManyToOne(targetEntity="Ulost\ObjectBundle\Entity\Object", inversedBy="questions", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $object;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="ordre", type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 10,
     *      )
     *
     */
    private $ordre;

    /**
     * @var int
     *
     * @ORM\Column(name="coefficient", type="integer", options={"default" : 1})
     * @Assert\Range(
     *      min = 0,
     *      max = 10,
     *      )
     *
     */
    private $coefficient;

    /**
     * @var string
     * @ORM\Column(name="typeQuestion", type="string", length=255)
     * @Assert\Choice({"option", "textarea", "checkbox"})
     */
    private $typeQuestion;



    /**
     * @var string
     * @ORM\Column(name="obligatoire", type="boolean", length=1, options={"default":0})
     *
     */
    private $obligatoire;

    /**
     * @ORM\OneToMany(targetEntity="Ulost\ObjectBundle\Entity\Alternative", mappedBy="question", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */

    private $alternatives;

    /**
     * @ORM\OneToMany(targetEntity="Ulost\AnnonceBundle\Entity\Reponse", mappedBy="question", cascade={"persist"})
     */
    private $reponses;


    public function __construct()
    {

        $this->object = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param mixed $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * @param int $ordre
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
    }

    /**
     * @return string
     */
    public function getTypeQuestion()
    {
        return $this->typeQuestion;
    }

    /**
     * @param string $typeQuestion
     */
    public function setTypeQuestion($typeQuestion)
    {
        $this->typeQuestion = $typeQuestion;
    }

    /**
     * @return string
     */
    public function getObligatoire()
    {
        return $this->obligatoire;
    }

    /**
     * @param string $obligatoire
     */
    public function setObligatoire($obligatoire)
    {
        $this->obligatoire = $obligatoire;
    }

    /**
     * @return mixed
     */
    public function getAlternatives()
    {
        return $this->alternatives;
    }


    /**
     * @param Alternative $alternative
     * @return $this
     */
    public function addAlternative(Alternative $alternative)
    {
        $this->alternatives[] = $alternative;
        $alternative->setQuestion($this);
        return $this;
    }

    /**
     * Remove alternative
     *
     * @param \Ulost\ObjectBundle\Entity\Alternative $alternative
     */
    public function removeAlternative(\Ulost\ObjectBundle\Entity\Alternative $alternative)
    {
        $this->alternatives->removeElement($alternative);
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
        $reponse->setQuestion($this);
        return $this;
    }


    public function removeReponse(Reponse $reponse)
    {
        $this->reponses->removeElement($reponse);
    }




    /**
     * @return int
     */
    public function getCoefficient()
    {
        return $this->coefficient;
    }

    /**
     * @param int $coefficient
     */
    public function setCoefficient($coefficient)
    {
        $this->coefficient = $coefficient;
    }


}
