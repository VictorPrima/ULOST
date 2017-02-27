<?php
// src/Blogger/BlogBundle/DataFixtures/ORM/BlogFixtures.php

namespace Ulost\UserBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Ulost\AnnonceBundle\Entity\Annonce;
use Ulost\AnnonceBundle\Entity\Reponse;
use Ulost\UserBundle\Entity\User;

class UserFixtures implements FixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    public function load(ObjectManager $manager)
    {


        /* TESTS UTILISATEURS
        $userManager = $this->container->get('fos_user.user_manager');
        $i = 1;
        while ($i < 10) {



            $name = "UtilisateurTest".$i;
            $mail=$name."@gmail.com";
            $password=$name;

            $user = $userManager->createUser();
            $user->setUsername($name);
            $user->setName($name);
            $user->setEmail($mail);
            $user->setPlainPassword($password);
            $user->setEnabled(true);
            $manager->persist($user);
            $manager->flush();
            $i++;
        }
*/

    }
}