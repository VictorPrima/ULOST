<?php

namespace Ulost\MunicipaleBundle\Controller;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Date;
use Ulost\AnnonceBundle\Entity\Annonce;
use Ulost\MunicipaleBundle\Entity\Service;
use Ulost\MunicipaleBundle\Entity\Stock;
use Ulost\MunicipaleBundle\Form\StockType;
use Ulost\MunicipaleBundle\Repository\EmplacementRepository;
use Ulost\MunicipaleBundle\Repository\StockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Ulost\VilleBundle\Entity\Ville;
use Ulost\VilleBundle\Repository\VilleRepository;
use Ulost\VilleBundle\UlostVilleBundle;


class StockController extends Controller
{
    public function indexStocksByServiceAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $service = $em->getRepository('UlostMunicipaleBundle:Service')->find($id);
        $session = $request->getSession();
        $session->set('service', $service);

        $queryStocks = $em
            ->getRepository('UlostMunicipaleBundle:Stock')
            ->findStocksByService($service);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $queryStocks,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        if (null === $queryStocks) {
            throw new NotFoundHttpException("Il n'y a pas de stocks à afficher");
        }

        return $this->render('UlostMunicipaleBundle:Stock:indexStockByService.html.twig', array(
            'pagination' => $pagination, 'service' => $service
        ));

    }

    public function indexStocksByEmplacementAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $emplacement = $em->getRepository('UlostMunicipaleBundle:Emplacement')->find($id);
        $service = $emplacement->getService();
        $session = $request->getSession();
        $session->set('service', $service);
        $listStocks = $em
            ->getRepository('UlostMunicipaleBundle:Stock')
            ->findByEmplacement($emplacement);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $listStocks,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        if (null === $listStocks) {
            throw new NotFoundHttpException("Il n'y a pas de stocks à afficher");
        }

        return $this->render('UlostMunicipaleBundle:Stock:indexStockByEmplacement.html.twig', array(
            'pagination' => $pagination, 'emplacement' => $emplacement
        ));

    }

    public function addStockEnfantAction(Request $request, $id)
    {

        $annonceParent = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->find($id);
        $annonce = $annonceParent;
        while ($annonce->getParent() != null) {
            $annonce = $annonce->getParent();
        }

        $session = $request->getSession();
        $session->set('annonceParent', $annonce->getId());

        $em = $this->getDoctrine()->getManager();
        $listCategory = $em
            ->getRepository('UlostObjectBundle:Category')
            ->findAll();
        return $this->render('UlostAnnonceBundle:Post:indexCategory.html.twig', array('status' => $annonceParent->getStatus(), 'listCategory' => $listCategory, 'annonceParent' => $annonceParent));


    }

    public function newStockAction(Request $request, $id, $status)
    {

        $session = $request->getSession();
        $session->clear();
        $service = $this->getDoctrine()->getRepository('UlostMunicipaleBundle:Service')->find($id);
        $session->set('service', $service);
        $session->set('status', $status);
        $em = $this->getDoctrine()->getManager();
        $listCategory = $em
            ->getRepository('UlostObjectBundle:Category')
            ->findAll();
        return $this->render('UlostAnnonceBundle:Post:indexCategory.html.twig', array('status' => $status, 'listCategory' => $listCategory));
    }


    public function addStockAction(Request $request, $id)
    {

        $session = $request->getSession();
        $service = $session->get('service');
        $annonce = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->find($id);

        $stock = new Stock();
        $service->addStock($stock);
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(StockType::class, $stock);


        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {


            $stock->setDateDepot(new \Datetime());
            $stock->setService($service);
            $stock = $em->merge($stock);


            if ($annonce->getParent() != null) {
                $annonce->getParent()->setStock($stock);
                $em->persist($stock);
                $em->flush();
                $emplacement = $stock->getEmplacement();
                $listEnfants = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->getAllAnnonceEnfantByAnnonce($annonce->getParent())->getResult();
                foreach ($listEnfants as $enfant) {
                    $enfant->setPublished(true);
                    if ($enfant->getStock() == null) {
                        $stock = new Stock();
                        $service->addStock($stock);
                        $stock->setEmplacement($emplacement);
                        $stock->setDateDepot(new \Datetime());
                        $stock->setService($service);
                        $stock = $em->merge($stock);
                        $enfant->setStock($stock);
                        $em->persist($stock);
                        $em->flush();
                    }
                }


            } else {
                $annonce->setStock($stock);
                $em->persist($stock);
                $em->flush();
            }


            $request->getSession()->getFlashBag()->add('notice',
                'L\'annonce ' . $annonce->getObject()->getTypeObjet() . ' a bien été ajoutée');


            return $this->redirectToRoute('ulost_service_accueil', array('id' => $service->getId()));
        }
        return $this->render('UlostMunicipaleBundle:Stock:addStock.html.twig', array(
                'form' => $form->createView(), 'service' => $service
            )
        );
    }


    public function recapStockAction($request, $annonce, $service)
    {
        $session = $request->getSession();
        $annonce->setPublished(true);
        $this->getDoctrine()->getManager()->persist($annonce);
        $this->getDoctrine()->getManager()->flush();
        if ($session->get('annonceParent') != null) {
            $annonceParent = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->find($session->get('annonceParent'));
            $annonce->setVille($annonceParent->getVille());
            return $this->render('UlostMunicipaleBundle:Stock:enregistrement.html.twig', array(
                'annonce' => $annonce, 'service' => $service
            ));
        }

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $annonce);


        $formBuilder
            ->add('ville', EntityType::class, array(
                'class' => 'UlostVilleBundle:Ville',
                'query_builder' => function (VilleRepository $er) use ($service) {
                    return $er
                        ->createQueryBuilder('v')
                        ->leftJoin('v.villeServiceRelations', 'r')
                        ->leftJoin('r.service', 's')
                        ->andWhere('s = :service')
                        ->setParameter('service', $service);
                },
                'choice_label' => 'name',
            ))
            ->add('save', SubmitType::class);


        $form = $formBuilder->getForm();


        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $this->getDoctrine()->getManager()->persist($annonce);
            $this->getDoctrine()->getManager()->flush();
            $request->getSession()->getFlashBag()->add('notice',
                'L\'annonce a bien été ajoutée');

            return $this->render('UlostMunicipaleBundle:Stock:enregistrement.html.twig', array(
                'annonce' => $annonce, 'service' => $service
            ));

        }
        return $this->render('UlostMunicipaleBundle:Stock:recap.html.twig', array(
            'form' => $form->createView(), 'annonce' => $annonce, 'service' => $service
        ));

    }


    public function editStockAction($id, Request $request)
    {
        if (!$id) {
            throw $this->createNotFoundException('Le stock ' . $id . 'n\'existe pas');
        }
        $stock = $this->getDoctrine()->getRepository('UlostMunicipaleBundle:Stock')->find($id);
        if (!$stock) {
            throw $this->createNotFoundException('Le stock ' . $id . 'n\'existe pas');
        }

        $service = $stock->getService();
        $form = $this->get('form.factory')->create(StockType::class, $stock);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($stock);
            $em->flush();


            $request->getSession()->getFlashBag()->add('notice', 'Le stock' . $stock->getAnnonce()->getObject()->getTypeObjet() . ' a été modifiée');


            return $this->redirectToRoute('ulost_index_stock_by_service', array('id' => $service->getId()));
        }

        return $this->render('UlostMunicipaleBundle:Stock:editStock.html.twig', array(
                'form' => $form->createView(), 'stock' => $stock, 'service' => $service
            )
        );
    }

    public function nbStockByServiceAction(Request $request, Service $service)
    {
        $nbStocks = $this->getDoctrine()->getRepository('UlostMunicipaleBundle:Stock')->countAllStocksByService($service);
        return $this->render('UlostMunicipaleBundle:Stock:showNbStocks.html.twig', array(
            'nbStocks' => $nbStocks
        ));
    }


    public function rechercheAction(Request $request)
    {
        $session = $request->getSession();
        $user = $this->getUser();
        $auth = $this->isGranted('ROLE_VILLE');

        if (!$session->get('municipales') || !$user || !$auth) {
            return $this->redirectToRoute('ulost_municipale_homepage');
        }

        $listeobjet = $this->container->get('ulost_objet')->showObjet();

        if (!$listeobjet) {
            throw $this->createNotFoundException('No object found');
        }

        return $this->render('UlostMunicipaleBundle:Stock:objet.html.twig',
            array('listeobjet' => $listeobjet));

    }

    public function objetAction(Request $request, $objet, $page)
    {
        //celui la retourne les resultats du stock mais uniquement sur un objet
        // avec une page en parametre
        return new Response('ok');
    }

    public function findAction(Request $request, $id)
    {
        //celui la recherche une annonce et un stock pour l'afficher
        //mais doit lui appartenir
        return new Response('ok');
    }
}
