<?php
// src/Ulost/UserBundle/Entity/User.php

namespace Ulost\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Ulost\AnnonceBundle\Entity\Annonce;

/**
 * @ORM\Entity(repositoryClass="Ulost\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Ulost\AnnonceBundle\Entity\Annonce", mappedBy="user", cascade={"persist"})
     **/

    private $annonces;


    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    protected $firstname;

    /**
     * @var int
     *
     * @ORM\Column(name="age", type="integer", nullable=true)
     */
    protected $age;

    /**
     * @var int
     *
     * @ORM\Column(name="user", type="integer", nullable=true)
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", nullable=true)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    protected $country;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=255, nullable=true)
     */
    protected $sexe;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth_date", type="date", nullable=true)
     */
    protected $birthDate;

    /**
     * @var int
     *
     * @ORM\Column(name="code_postal", type="integer", nullable=true)
     */
    protected $codePostal;

    /**
     * @var string
     *
     * @ORM\Column(name="town", type="string", length=255, nullable=true)
     */
    protected $town;

    /**
     * @var string
     *
     * @ORM\Column(name="nationality", type="string", length=255, nullable=true)
     */
    protected $nationality;

    /**
     * @var int
     *
     * @ORM\Column(name="profile_picture", type="integer", nullable=true)
     */
    protected $profilePicture;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_lost", type="integer", nullable=true)
     */
    protected $nbLost;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_found", type="integer", nullable=true)
     */
    protected $nbFound;

    /**
     * @var string
     *
     * @ORM\Column(name="grade", type="string", length=255, nullable=true)
     */
    protected $grade;

    /**
     * 
     * @ORM\OneToMany(targetEntity="Ulost\MunicipaleBundle\Entity\Emploi", mappedBy="user", cascade={"persist"})
     *
     */
    private $emplois;


    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set age
     *
     * @param integer $age
     *
     * @return User
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set user
     *
     * @param integer $user
     *
     * @return User
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return integer
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set sexe
     *
     * @param string $sexe
     *
     * @return User
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return string
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return User
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set codePostal
     *
     * @param integer $codePostal
     *
     * @return User
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return integer
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set town
     *
     * @param string $town
     *
     * @return User
     */
    public function setTown($town)
    {
        $this->town = $town;

        return $this;
    }

    /**
     * Get town
     *
     * @return string
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * Set nationality
     *
     * @param string $nationality
     *
     * @return User
     */
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * Get nationality
     *
     * @return string
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * Set profilePicture
     *
     * @param integer $profilePicture
     *
     * @return User
     */
    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    /**
     * Get profilePicture
     *
     * @return integer
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * Set nbLost
     *
     * @param integer $nbLost
     *
     * @return User
     */
    public function setNbLost($nbLost)
    {
        $this->nbLost = $nbLost;

        return $this;
    }

    /**
     * Get nbLost
     *
     * @return integer
     */
    public function getNbLost()
    {
        return $this->nbLost;
    }

    /**
     * Set nbFound
     *
     * @param integer $nbFound
     *
     * @return User
     */
    public function setNbFound($nbFound)
    {
        $this->nbFound = $nbFound;

        return $this;
    }

    /**
     * Get nbFound
     *
     * @return integer
     */
    public function getNbFound()
    {
        return $this->nbFound;
    }

    /**
     * Set grade
     *
     * @param string $grade
     *
     * @return User
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return string
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Add annonce
     *
     * @param \Ulost\AnnonceBundle\Entity\Annonce $annonce
     *
     * @return User
     */
    public function addAnnonce(\Ulost\AnnonceBundle\Entity\Annonce $annonce)
    {
        $this->annonces[] = $annonce;
        $annonce->setUser($this);
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

    /**
     * Add emplois
     *
     * @param \Ulost\MunicipaleBundle\Entity\Emploi $emplois
     *
     * @return User
     */
    public function addEmplois(\Ulost\MunicipaleBundle\Entity\Emploi $emplois)
    {
        $this->emplois[] = $emplois;

        return $this;
    }

    /**
     * Remove emplois
     *
     * @param \Ulost\MunicipaleBundle\Entity\Emploi $emplois
     */
    public function removeEmplois(\Ulost\MunicipaleBundle\Entity\Emploi $emplois)
    {
        $this->emplois->removeElement($emplois);
    }

    /**
     * Get emplois
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmplois()
    {
        return $this->emplois;
    }
}
