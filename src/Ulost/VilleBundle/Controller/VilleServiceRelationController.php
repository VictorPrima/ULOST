<?php

namespace Ulost\VilleBundle\Controller;




use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Ulost\VilleBundle\Entity\Ville;
use Ulost\VilleBundle\Entity\VilleServiceRelation;
use Ulost\MunicipaleBundle\Entity\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Ulost\VilleBundle\Form\VilleServiceRelationType;



class VilleServiceRelationController extends Controller
{

    public function addVilletoServiceAction(Request $request, Service $service)
    {

        // Création de l'entité VilleServiceRelation
        $villeServiceRelation = new VilleServiceRelation();


        $form = $this->get('form.factory')->create(VilleServiceRelationType::class, $villeServiceRelation);


        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $villeId = $request->get('villeId');
            $ville = $this->getDoctrine()->getRepository('UlostVilleBundle:Ville')->find($villeId);
            $villeServiceRelation->setService($service);
            $villeServiceRelation->setVille($ville);
            $villeServiceRelation->setPrincipale(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($villeServiceRelation);
            $em->flush();


            $request->getSession()->getFlashBag()->add('notice',
                'La ville '.$ville->getName().' a bien été ajoutée au service '. $service->getName());
        }
        return $this->render('UlostVilleBundle:VilleServiceRelation:addVilleServiceRelation.html.twig', array(
                'form' => $form->createView(),'service'=>$service
            )
        );
    }




    public function indexVilleServiceRelationAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $listVilleServiceRelation = $em
            ->getRepository('UlostVilleBundle:VilleServiceRelation')
            ->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $listVilleServiceRelation,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );
        if (null === $listVilleServiceRelation) {
            throw new NotFoundHttpException("Il n'y a pas d'VilleServiceRelations à afficher");
        }

        return $this->render('UlostVilleBundle:VilleServiceRelation:indexAllVilleByService.html.twig', array('pagination' => $pagination));
    }

    public function viewRelationByVilleAndServiceAction(Request $request, Service $service, Ville $ville)
    {
        $em = $this->getDoctrine()->getManager();
        $villeServiceRelation= $em
            ->getRepository('UlostVilleBundle:VilleServiceRelation')
            ->findOneBy(array(
                'service'=>$service,
                'ville'=>$ville
            ));

        if (null === $villeServiceRelation) {
            throw new NotFoundHttpException("Il n'y a pas de VilleServiceRelation à afficher à afficher");
        }

        return $this->redirectToRoute('ulost_view_villeServiceRelation', array(
            'id' => $villeServiceRelation->getId()
        ));

    }

    public function viewVilleServiceRelationAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $villeServiceRelation= $em
            ->getRepository('UlostVilleBundle:VilleServiceRelation')
            ->find($id);

        if (null === $villeServiceRelation) {
            throw new NotFoundHttpException("Il n'y a pas de VilleServiceRelation à afficher à afficher");
        }

        return $this->render('UlostVilleBundle:VilleServiceRelation:viewVilleServiceRelation.html.twig', array(
            'villeServiceRelation' => $villeServiceRelation,
        ));

    }


    public function editVilleServiceRelationAction($id, Request $request)
    {
        if (!$id) {
            throw $this->createNotFoundException('L\'VilleServiceRelation ' . $id . 'n\'existe pas');
        }
        $villeServiceRelation = $this->getDoctrine()->getRepository('UlostVilleBundle:VilleServiceRelation')->find($id);
        if (!$villeServiceRelation) {
            throw $this->createNotFoundException('L\'VilleServiceRelation ' . $id . 'n\'existe pas');
        }

        $form = $this->get('form.factory')->create(VilleServiceRelationType::class, $villeServiceRelation);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($villeServiceRelation);
            $em->flush();


            $request->getSession()->getFlashBag()->add('notice', 'L\'VilleServiceRelation' . $villeServiceRelation->getName() . ' a été modifiée');

        }

        return $this->render('UlostVilleBundle:VilleServiceRelation:editVilleServiceRelation.html.twig', array(
                'form' => $form->createView(),'villeServiceRelation'=>$villeServiceRelation
            )
        );
    }

    public function indexVilleByServiceAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $service=$em->getRepository('UlostMunicipaleBundle:Service')->find($id);
        $listVille = $em
            ->getRepository('UlostVilleBundle:Ville')
            ->findVilleByService($service);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $listVille,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        if (null === $listVille) {
            throw new NotFoundHttpException("Il n'y a pas d'VilleServiceRelation à afficher");
        }

        return $this->render('UlostVilleBundle:VilleServiceRelation:indexVilleByService.html.twig', array(
            'pagination' => $pagination, 'service'=>$service
        ));

    }

    public function showRelationByVilleAndServiceAction(Request $request, Service $service, Ville $ville){
        $em = $this->getDoctrine()->getManager();
        $villeServiceRelation= $em
            ->getRepository('UlostVilleBundle:VilleServiceRelation')
            ->findOneBy(array(
                'service'=>$service,
                'ville'=>$ville
            ));
return $this->render('UlostVilleBundle:VilleServiceRelation:showVilleByService.html.twig', array(
    'villeServiceRelation'=>$villeServiceRelation
));
    }








}