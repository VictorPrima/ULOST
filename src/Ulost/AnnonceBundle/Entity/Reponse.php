<?php

namespace Ulost\AnnonceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ulost\UserBundle\Entity\User;
use Ulost\ObjectBundle\Entity\Object;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Reponse
 *
 * @ORM\Table(name="reponse")
 * @ORM\Entity(repositoryClass="Ulost\AnnonceBundle\Repository\ReponseRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Reponse
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
     * @ORM\Column(name="champ", type="text", length=255)
     */

    private $champ;


    /**
     * @ORM\ManyToOne(targetEntity="Ulost\AnnonceBundle\Entity\Annonce", inversedBy="reponses", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $annonce;


    /**
     * @ORM\ManyToOne(targetEntity="Ulost\ObjectBundle\Entity\Question", inversedBy="reponses", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

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
    public function getAnnonce()
    {
        return $this->annonce;
    }

    /**
     * @param mixed $annonce
     */
    public function setAnnonce($annonce)
    {
        $this->annonce = $annonce;
    }

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param mixed $question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * @return string
     */
    public function getChamp()
    {
        return $this->champ;
    }

    /**
     * @param string $champ
     */
    public function setChamp($champ)
    {
        $this->champ = $champ;
    }

    public function __construct()
    {
        $this->annonce = new ArrayCollection();
        $this->question = new ArrayCollection();
    }



}
