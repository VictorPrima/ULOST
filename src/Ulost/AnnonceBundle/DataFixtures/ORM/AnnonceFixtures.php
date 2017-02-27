<?php
// src/Blogger/BlogBundle/DataFixtures/ORM/BlogFixtures.php

namespace Ulost\AnnonceBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ulost\AnnonceBundle\Entity\Annonce;
use Ulost\AnnonceBundle\Entity\Blog;
use Ulost\AnnonceBundle\Entity\Reponse;
use Ulost\MunicipaleBundle\Entity\Stock;

class AnnonceFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {


        /* TESTS TELEPHONE */

        $object1 = $manager->getRepository('UlostObjectBundle:Object')->find(3);
        $object2 = $manager->getRepository('UlostObjectBundle:Object')->find(4);
        $object3 = $manager->getRepository('UlostObjectBundle:Object')->find(29);
        $arrayObject = [$object1,$object2,$object3];
        $marque1 = 'Apple';
        $marque2 = 'Samsung';
        $marque3 = 'Sony';
        $arrayMarque = [$marque1, $marque2, $marque3];
        $modele1 = 'iPhone 4';
        $modele2 = 'iPhone 5';
        $modele3 = 'iPhone 6';
        $modele4 = 'Galaxy 5';
        $modele5 = 'Galaxy 6';
        $modele6 = 'XPeria 5';
        $modele7 = 'XPeria 6';
        $couleur1 = 'Blanc';
        $couleur2 = 'Noir';
        $couleur3 = 'Gris';
        $arrayCouleur = [$couleur1, $couleur2, $couleur3];
        $ville1 = $manager->getRepository('UlostVilleBundle:Ville')->find(26018);
        $ville2 = $manager->getRepository('UlostVilleBundle:Ville')->find(26004);
        $ville3 = $manager->getRepository('UlostVilleBundle:Ville')->find(26000);
        $ville4 = $manager->getRepository('UlostVilleBundle:Ville')->find(9430);
        $arrayVille = [$ville1, $ville2, $ville3, $ville4];
        $user1 = $manager->getRepository('UlostUserBundle:User')->find(10);
        $user2 = $manager->getRepository('UlostUserBundle:User')->find(11);
        $user3 = $manager->getRepository('UlostUserBundle:User')->find(12);
        $user4 = $manager->getRepository('UlostUserBundle:User')->find(13);
        $user5 = $manager->getRepository('UlostUserBundle:User')->find(14);
        $user6 = $manager->getRepository('UlostUserBundle:User')->find(15);
        $user8 = $manager->getRepository('UlostUserBundle:User')->find(17);
        $user9 = $manager->getRepository('UlostUserBundle:User')->find(18);
        $arrayUser = [$user1, $user2, $user3, $user4, $user5, $user6, $user8, $user9];

        $i = 1;
        while ($i < 100) {

            $annonce = new Annonce();
            $j = array_rand($arrayObject);
            $object = $arrayObject[$j];
            $annonce->setObject($object);

            /*on choisit une ville au hasard*/
            $j = array_rand($arrayVille);
            $ville = $arrayVille[$j];

            /*on choisit un user au hasard*/
            $j = array_rand($arrayUser);
            $user = $arrayUser[$j];


            /*on choisit une marque au hasard*/
            $j = rand(1, 3);
            if ($j == 1) {
                $marque = $marque1;
                /*onchoisit un modèle au hasard*/
                $k = rand(1, 3);
                if ($k == 1) {
                    $modele = $modele1;
                } elseif ($k == 2) {
                    $modele = $modele2;
                } else {
                    $modele = $modele3;
                }
            } elseif ($j == 2) {
                $marque = $marque2;
                /*onchoisit un modèle au hasard*/
                $k = rand(1, 2);
                if ($k == 1) {
                    $modele = $modele4;
                } else {
                    $modele = $modele5;
                }
            } else {
                $marque = $marque3;
                /*onchoisit un modèle au hasard*/
                $k = rand(1, 2);
                if ($k == 1) {
                    $modele = $modele6;
                } else {
                    $modele = $modele7;
                }

            }


            /*on choisit une couleur au hasard*/
            $j = array_rand($arrayCouleur);
            $couleur = $arrayCouleur[$j];

            /*On choisit un statut au hasard*/
            $j = rand(1, 2);
            if ($j == 1) {
                $status = 'lost';
            } else {
                $status = 'found';
                //si le status est "found", on peut poster l'annonce dans un service
                if (rand(0, 1) == 1) {
                    $listService = $manager->getRepository('UlostMunicipaleBundle:Service')
                        ->getServiceFromVille($ville)
                        ->getQuery()
                        ->getResult();;

                    $serviceKey = array_rand($listService);
                    $service = $listService[$serviceKey];


                    //on prend un emplacement au hasard
                    $listEmplacements = $manager->getRepository('UlostMunicipaleBundle:Emplacement')
                        ->findByService($service);

                    $emplacementKey = array_rand($listEmplacements);
                    $emplacement = $listEmplacements[$emplacementKey];

                    $stock = new Stock();
                    $stock->setEmplacement($emplacement);
                    $service->addStock($stock);
                    $stock->setDateDepot(new \Datetime());
                    $annonce->setStock($stock);
                }
            }
//on complète le formulaire
            $listQuestion = $manager
                ->getRepository('UlostObjectBundle:Question')
                ->findByObject($object, array('ordre' => 'ASC'));
            foreach ($listQuestion as $question){
                if (in_array($question->getId(), [17, 8, 13] )){
                    $reponse1 = new Reponse();
                    $reponse1->setQuestion($question);
                    $reponse1->setChamp($marque);
                    $annonce->addReponse($reponse1);

                } elseif ($question->getId() == 23){
                    $reponse2 = new Reponse();
                    $reponse2->setQuestion($question);
                    $reponse2->setChamp($modele);
                    $annonce->addReponse($reponse2);

                } elseif (in_array($question->getId(), [73,74,75])){
                    $reponse3 = new Reponse();
                    $reponse3->setQuestion($question);
                    $reponse3->setChamp($couleur);
                    $annonce->addReponse($reponse3);

                } elseif ($question->getTypeQuestion() == "option"){
                    $listAlternatives = $question->getAlternatives();
                    $listChoix = array();
                    foreach ($listAlternatives as $alternative) {
                        $listChoix[] = $alternative;
                    }
                    $champ = array_rand($listChoix, 1);
                    $reponse = new Reponse();
                    $reponse->setChamp($champ);
                    $reponse->setQuestion($question);
                    $annonce->addReponse($reponse);

                } elseif ($question->getTypeQuestion() == "checkbox") {
                    $reponse = new Reponse();
                    $reponse->setChamp(rand(0, 1) == 1);
                    $reponse->setQuestion($question);
                    $annonce->addReponse($reponse);

                } else {
                    $reponse = new Reponse();
                    $reponse->setChamp("Pas de réponse");
                    $reponse->setQuestion($question);
                    $annonce->addReponse($reponse);

                }
            }


            $annonce->setVille($ville);
            $annonce->setUser($user);
            $remarque = "Test " . $i;
            $annonce->setStatus($status);
            $annonce->setRemarque($remarque);
            $annonce->setPublished(true);
            $annonce->setArchived(false);
            $annonce->setDate(new \Datetime());
            $manager->persist($annonce);
            $manager->flush();
            $i++;
        }

    }
}