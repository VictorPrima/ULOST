<?php

namespace Ulost\CorrespondanceBundle\Controller;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Ulost\AnnonceBundle\Entity\Annonce;
use Ulost\AnnonceBundle\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Ulost\CorrespondanceBundle\Entity\Correspondance;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Ulost\MunicipaleBundle\Entity\Service;

class CorrespondanceController extends Controller
{


    public function indexUserAction(Request $request)
    {
        $user = $this->getUser();
        return $this->indexCorrespondancesByUserAction($user->getId());
    }

    public function indexAnnoncesCorrespondantesAction(Request $request, $id, $page)
    {

        $annonce = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->find($id);
        $session = $request->getSession();
        $session->set('annonce', $annonce);
        return $this->render('UlostCorrespondanceBundle:Correspondance:indexAnnoncesCorrespondantes.html.twig', array('annonce' => $annonce, 'page' => $page));
    }


    public function showAnnoncesCorrespondantesByIdAction(Request $request, $id, $page)
    {

        $annonce = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->find($id);
        $listAnnoncesCorrespondantes = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->getCorrespondance1($annonce);

        foreach ($listAnnoncesCorrespondantes as $annonceCorrespondante) {

            if ($this->comparaisonAction($annonce, $annonceCorrespondante) == true) {

                $request->getSession()->getFlashBag()->add('notice',
                    'L\'annonce ' . $annonceCorrespondante->getId() . ' est déjà correspondante avec l\'annonce '.$annonce->getId());
            } else {
                if ($annonce->getStatus() == 'lost') {
                    $lost = $annonce;
                    $found = $annonceCorrespondante;
                } else {
                    $lost = $annonceCorrespondante;
                    $found = $annonce;
                }
                $this->addCorrespondanceAction($request, $lost, $found);
            }
        }
        $this->denyAccessUnlessGranted('view', $annonce);
        $query = $this->getDoctrine()->getRepository('UlostCorrespondanceBundle:Correspondance')->findCorrespondanceFromAnnonce($annonce);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page)/*page number*/,
            50/*limit per page*/

        );

        return $this->render('UlostCorrespondanceBundle:Correspondance:listCorrespondances.html.twig',
            array('pagination' => $pagination, 'annonceParent' => $annonce
            ));

    }

    public function showMetaAnnoncesCorrespondantesAction($annonce_id, $annonceParent)
    {

        $annonce = $this->getDoctrine()->getManager()
            ->getRepository('UlostAnnonceBundle:Annonce')->find($annonce_id);

        return $this->render('UlostCorrespondanceBundle:Correspondance:showMetaAnnoncesCorrespondantes.html.twig',
            array('annonceCorrespondance' => $annonce, 'annonceParent' => $annonceParent
            ));
    }

    public function showComparaisonAction($id)
    {
        $correspondance = $this->getDoctrine()->getManager()
            ->getRepository('UlostCorrespondanceBundle:Correspondance')->find($id);
        $this->denyAccessUnlessGranted('view', $correspondance);
        $lost = $this->getAnnonceAction($correspondance->getLost());
        $found = $this->getAnnonceAction($correspondance->getFound());
        if ($lost->getUser() == $this->getUser()) {
            $annonce = $lost;
            $annonceCorrespondance = $found;
        } else {
            $annonce = $found;
            $annonceCorrespondance = $lost;
        }
        if ($correspondance->getConfirmed()) {
            return $this->render('UlostCorrespondanceBundle:Correspondance:showCorrespondance.html.twig',
                array(
                    'correspondance' => $correspondance,
                    'annonceCorrespondance' => $annonceCorrespondance,
                    'annonce' => $annonce
                ));
        } else {
            return $this->render('UlostCorrespondanceBundle:Correspondance:showComparaison.html.twig',
                array(
                    'correspondance' => $correspondance,
                    'annonceCorrespondance' => $annonceCorrespondance,
                    'annonce' => $annonce
                ));
        }
    }


    public function addCorrespondanceAction(Request $request, $lost, $found)
    {

        $correspondance = new Correspondance();
        $correspondance->addFound($found);
        $correspondance->addLost($lost);
        $correspondance->setConfirmed(false);
        $correspondance->setArchived(false);
        $correspondance->setDate(new \DateTime());
        $em = $this->getDoctrine()->getManager();
        $em->persist($correspondance);
        $em->flush();


        $request->getSession()->getFlashBag()->add('notice',
            'La correspondance ' . $correspondance->getId() . ' a été ajoutée');


        return true;
    }

    public function newCorrespondanceAction(Request $request, $lost_id, $found_id)
    {
        $lost = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->find($lost_id);
        $found = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->find($found_id);
        if ($this->addCorrespondanceAction($request, $lost, $found)) {
            return $this->render('UlostCorrespondanceBundle:Correspondance:showComparaison.html.twig',
                array('annonceCorrespondance' => $lost, 'annonce' => $found
                ));
        } else {
            return $this->redirectToRoute('ulost_oneannoncepage', array('id' => $lost_id));
        }
    }

    public function comparaisonAction($annonce1, $annonce2)
    {
        if ($annonce1->getStatus() == 'lost') {
            $lost = $annonce1;
            $found = $annonce2;
        } else {
            $lost = $annonce2;
            $found = $annonce1;
        }
        $correspondance = $this->getDoctrine()
            ->getRepository('UlostCorrespondanceBundle:Correspondance')->getCorrespondanceByAnnonces($lost, $found);

        if ($correspondance != 0
        ) {

            return true;
        } else {
            return false;
        }
    }


    public function showMetaCorrespondanceAction($id, $annonceParent)
    {

        $correspondance = $this->getDoctrine()->getManager()
            ->getRepository('UlostCorrespondanceBundle:Correspondance')->find($id);

        if ($annonceParent->getStatus() == 'lost') {
            $annonce = array_values($correspondance->getLost()->toArray())[0];
        } else {
            $annonce = array_values($correspondance->getFound()->toArray())[0];
        }


        return $this->render('UlostCorrespondanceBundle:Correspondance:showMetaCorrespondance.html.twig',
            array(
                'correspondance' => $correspondance,
                'annonce' => $annonce
            ));
    }


    public function showContactAnnonceCorrespondanteAction(Annonce $annonceCorrespondance)
    {


        if ($annonceCorrespondance->getStock() != null) {
            $stock = $annonceCorrespondance->getStock();
            $service = $stock->getService();
            return $this->render('UlostCorrespondanceBundle:Correspondance:showServiceAnnonceCorrespondante.html.twig', array(
                'service' => $service
            ));
        } else {
            $user = $annonceCorrespondance->getUser();
        }


        return $this->render('UlostCorrespondanceBundle:Correspondance:showUserAnnonceCorrespondante.html.twig', array(
            'user' => $user
        ));


    }

    public
    function indexCorrespondancesByServiceAction(Request $request, $id, $page)
    {
        $session = $request->getSession();

        $service = $this->getDoctrine()->getRepository('UlostMunicipaleBundle:Service')->find($id);
        if ($request->getMethod() == 'POST') {
            $object_id = $request->get('objectId');
            $object = $this->getDoctrine()->getRepository("UlostObjectBundle:Object")->find($object_id);
            $session->set('parameter_objet', $object);
        }

        return $this->render('UlostCorrespondanceBundle:Correspondance:indexCorrespondancesByService.html.twig', array(
            'service' => $service, 'page' => $page));
    }

    public
    function showCorrespondancesByServiceAction(Request $request, $id, $page)
    {
        $session = $request->getSession();
        $service = $this->getDoctrine()->getRepository('UlostMunicipaleBundle:Service')->find($id);




            $query = $this->getDoctrine()->getRepository('UlostCorrespondanceBundle:Correspondance')
                ->findCorrespondancesByService(
                   $service
                );

            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', $page)/*page number*/,
                50/*limit per page*/
            );

        return $this->render('UlostCorrespondanceBundle:Correspondance:listCorrespondances.html.twig',
            array('pagination' => $pagination,
            ));

    }

    public
    function confirmerCorrespondanceAction($id)
    {
        $correspondance = $this->getDoctrine()->getManager()
            ->getRepository('UlostCorrespondanceBundle:Correspondance')->find($id);
        $correspondance->setConfirmed(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($correspondance);
        $em->flush();
        $lost = array_values($correspondance->getLost()->toArray())[0];
        $found = array_values($correspondance->getFound()->toArray())[0];
        $subject = "Correspondance confirmée : " . $lost->getObject()->getTypeObjet();
        $url = $this->generateUrl('ulost_comparaison_correspondance', array('id' => $correspondance->getId()), UrlGeneratorInterface::ABSOLUTE_URL);
        $body =
            "Bonjour , votre annonce concernant " . $lost->getObject()->getTypeObjet() .
            " a une correspondance. Vous pouvez la retrouver à cette adresse :" . $url . "
            Pensez à revenir régulièrement sur ulost.fr pour vérifier si des correspondances ont été trouvées.
            Cordialement";
        $this->get('MailAction')->sendEmailAction($lost->getUser(), $subject, $body);
        $this->get('MailAction')->sendEmailAction($found->getUser(), $subject, $body);
        return $this->showComparaisonAction($correspondance->getId());
    }

    public
    function annulerConfirmationAction($id)
    {
        $correspondance = $this->getDoctrine()->getManager()
            ->getRepository('UlostCorrespondanceBundle:Correspondance')->find($id);
        $correspondance->setConfirmed(false);
        $em = $this->getDoctrine()->getManager();
        $em->persist($correspondance);
        $em->flush();
        return $this->redirect($this->generateUrl("ulost_comparaison_correspondance", array('id' => $id)));
    }

    public
    function nbCorrespondancesByAnnonceAction(Annonce $annonce)
    {
        $nbCorrespondances = $this->getDoctrine()
            ->getRepository('UlostCorrespondanceBundle:Correspondance')
            ->countAllCorrespondancesByAnnonce($annonce);
        return $this->render('UlostCorrespondanceBundle:Correspondance:showNbCorrespondances.html.twig', array(
            'nbCorrespondances' => $nbCorrespondances
        ));
    }

    public
    function nbCorrespondancesByServiceAction(Service $service)
    {
        $nbCorrespondances = $this->getDoctrine()
            ->getRepository('UlostCorrespondanceBundle:Correspondance')
            ->countAllCorrespondancesByService($service);
        return $this->render('UlostCorrespondanceBundle:Correspondance:showNbCorrespondances.html.twig', array(
            'nbCorrespondances' => $nbCorrespondances
        ));
    }

    public
    function getAnnonceAction($variable)
    {
        return array_values($variable->toArray())[0];
    }

    public
    function archiverCorrespondanceAction(Request $request, $id)
    {
        $correspondance = $this->getDoctrine()->getRepository('UlostCorrespondanceBundle:Correspondance')->find($id);
        if ($this->archiverAction($request, $correspondance)) {
            if ($request->getSession()->has('annonce')) {
                $annonce = $request->getSession()->get('annonce');
                return $this->redirectToRoute('ulost_Correspondance1', array(
                    'id' => $annonce->getId()
                ));
            } else {
                $service = $request->getSession()->get('service');
                return $this->redirectToRoute('ulost_index_correspondance_by_service', array(
                    'id' => $service->getId()
                ));
            }
        } else {
            throw $this->createNotFoundException('La correspondance n\a pas pu être archivée');
        }
    }

    public function archiverAction(Request $request, Correspondance $correspondance)
    {
        if ($correspondance->getConfirmed()) {
            $lost = $this->getAnnonceAction($correspondance->getLost());
            $found = $this->getAnnonceAction($correspondance->getFound());
            $this->get('AnnonceAction')->archiverAction($request, $lost);
            $this->get('AnnonceAction')->archiverAction($request, $found);
        }
        $correspondance->setArchived(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($correspondance);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice',
            'La correspondance ' . $correspondance->getId() . ' a bien été archivée');

        return true;
    }

    public function removeCorrespondanceAction(Request $request, Correspondance $correspondance)
    {
        $id = $correspondance->getId();
        $em = $this->getDoctrine()->getManager();
        $em->remove($correspondance);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice',
            'La correspondance ' . $id . ' a bien été supprimée');
        return true;
    }

    public
    function removeAllCorrespondancesFromAnnonceAction(Request $request, Annonce $annonce)
    {
        $listCorrespondances = $this->getDoctrine()->getRepository('UlostCorrespondanceBundle:Correspondance')->findCorrespondanceFromAnnonce($annonce);
        foreach ($listCorrespondances as $correspondance) {
            $this->removeCorrespondanceAction($request, $correspondance);
        }
        $listCorrespondances = $this->getDoctrine()->getRepository('UlostCorrespondanceBundle:Correspondance')->findCorrespondanceFromAnnonce($annonce);
        foreach ($listCorrespondances as $correspondance) {
            $this->removeCorrespondanceAction($request, $correspondance);
        }
        $request->getSession()->getFlashBag()->add('notice',
            'Les correspondances de l\'annonce ' . $annonce->getId() . ' ont bien été supprimées');
        return true;
    }

    public
    function archiverAllCorrespondancesFromAnnonceAction(Request $request, Annonce $annonce)
    {
        $listCorrespondances = $this->getDoctrine()->getRepository('UlostCorrespondanceBundle:Correspondance')->findCorrespondanceFromAnnonce($annonce);
        foreach ($listCorrespondances as $correspondance) {
            $this->archiverCorrespondanceAction($request, $correspondance);

        }
        $request->getSession()->getFlashBag()->add('notice',
            'Les correspondances de l\'annonce ' . $annonce->getId() . ' ont bien été archivées');
        return true;
    }

    public  function updateAllCorrespondanceAction(Request $request)
    {

        $listAnnonces = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->findAll();

        foreach ($listAnnonces as $annonce) {

            $listAnnoncesCorrespondantes = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->getCorrespondance1($annonce);

            foreach ($listAnnoncesCorrespondantes as $annonceCorrespondante) {
                if ($this->comparaisonAction($annonce, $annonceCorrespondante) == true) {

                } else {
                    if ($annonce->getStatus() == 'lost') {
                        $lost = $annonce;
                        $found = $annonceCorrespondante;
                    } else {
                        $lost = $annonceCorrespondante;
                        $found = $annonce;
                    }
                    $this->addCorrespondanceAction($request, $lost, $found);
                }
            }
        }
        $request->getSession()->getFlashBag()->add('notice',
            'Les correspondances ont été mises à jour');

        return $this->redirectToRoute('ulost_home');
    }

    public
    function calculDistanceAction(Correspondance $correspondance)
    {
        $lost = $this->getAnnonceAction($correspondance->getLost());
        $found = $this->getAnnonceAction($correspondance->getFound());
        $listReponsesLost = $lost->getReponses();
        $distanceTotale = 0;
        foreach ($listReponsesLost as $reponseLost) {
            $question = $reponseLost->getQuestion();
            $reponseFound = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Reponse')->findByAnnonceAndQuestion($found, $question);
            $champReponseFound = $this->champToStringAction($this->get('AnnonceAction')->getChampReponseAction($reponseFound));
            $champReponseLost = $this->champToStringAction($this->get('AnnonceAction')->getChampReponseAction($reponseLost));
            $distance = levenshtein($champReponseFound, $champReponseLost);
            $distanceTotale = $distanceTotale + $question->getCoefficient() * $distance;
        }
        return $distanceTotale;

    }

    public
    function showDistanceAction(Correspondance $correspondance)
    {
        $distance = $this->calculDistanceAction($correspondance);

        return $this->render('UlostCorrespondanceBundle:Correspondance:showDistance.html.twig', array(
            'distance' => $distance
        ));
    }

    public
    function getMinDistanceAction(Annonce $annonce)
    {
        $distanceMin = 100000;
        $listCorrespondances = $this->getDoctrine()->getRepository('UlostCorrespondanceBundle:Correspondance')
            ->findCorrespondanceFromAnnonce($annonce)->getQuery()->getResult();

        foreach ($listCorrespondances as $correspondance) {
            $distance = $this->calculDistanceAction($correspondance);

            $distanceMin = min($distanceMin, $distance);
        }
        return $distanceMin;
    }

    public
    function showDistanceMinAction(Annonce $annonce)
    {
        $distanceMin = $this->getMinDistanceAction($annonce);
        return $this->render('UlostCorrespondanceBundle:Correspondance:showDistance.html.twig', array(
            'distance' => $distanceMin
        ));
    }

    public function champToStringAction($champ)
    {
        return $string = strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($champ, ENT_QUOTES, 'UTF-8'))), ' '));

    }

    public function showChampAction($champ)
    {
        $champfinal = strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($champ, ENT_QUOTES, 'UTF-8'))), ' '));
        return $this->render('UlostCorrespondanceBundle:Correspondance:showChamp.html.twig', array(
            'champ' => $champfinal
        ));
    }
}
