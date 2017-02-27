<?php

namespace Ulost\ObjectBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ulost\ObjectBundle\Entity\Question;
use Symfony\Component\HttpFoundation\Tests\StringableObject;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Alternative
 *
 * @ORM\Table(name="alternative")
 * @ORM\Entity(repositoryClass="Ulost\ObjectBundle\Repository\AlternativeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Alternative{
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
     * @ORM\ManyToOne(targetEntity="Ulost\ObjectBundle\Entity\Question", inversedBy="alternatives")
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
     * @return mixed
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param mixed $question
     */
    public function setQuestion(Question $question)
    {
        $this->question = $question;

    }






}
