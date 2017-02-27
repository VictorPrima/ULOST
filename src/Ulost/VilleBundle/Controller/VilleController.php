<?php

namespace Ulost\VilleBundle\Controller;

use Elastica\Query\QueryString;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Ulost\AnnonceBundle\Entity\Annonce;
use Ulost\VilleBundle\Entity\Ville;
use Ulost\VilleBundle\Form\VilleType;
use JMS\Serializer\SerializerBuilder;

class VilleController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        return $this->render('UlostVilleBundle:Ville:index.html.twig');
    }

    public function getVilleAction(Request $request,Annonce $annonce)
    {
        $session = $request->getSession();
        $ville = New Ville();
        $form = $this->createForm(VilleType::class, $ville);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {


            $session->set('ville', $ville);



            return $this->redirectToRoute('ulost_annonce_enregistrer', array('id'=>$annonce->getId()));
        }
        else{
            return $this->render('UlostVilleBundle:Ville:formVille.html.twig', array(
                'form' => $form->createView(),'annonce'=>$annonce
            ));
        }
    }


    /** public function getVilleAction(Request $request,Annonce $annonce)
    {
    $session = $request->getSession();
    return $this->render('UlostVilleBundle:Ville:index.html.twig');

    }



    public function villeSuggest2Action(Request $request,$cp)
    {
    $em=$this->getDoctrine()->getManager();
    $ville = $em ->getRepository('UlostVilleBundle:Ville')->findOneBy(array('codePostal'=>$cp));
    if ($ville)
    {
    $name=$ville->getName();
    }
    else {
    $name=null;
    }
    $response = new JsonResponse();
    return $response->setData(array('ville'=>$name));
    }
     *
     *
     * **/
    public function searchAction(Request $request)
    {

        $term = $request->get('search', null);
        $serializer = SerializerBuilder::create()->build();

        $em = $this->getDoctrine()->getManager();

        $dql = "SELECT v FROM UlostVilleBundle:Ville v WHERE v.name LIKE '" . $term . "%' ORDER BY v.name ASC";
        $query = $em->createQuery($dql);
        $results = $query->getResult();

        $data = array();

        // on arrange les données des résultats...
        foreach ($results as $source) {

            $data[] = array(
                'suggest'   => $source->getcodePostal().' '.$source->getName(),
                'codePostal' => $source->getCodePostal(),
                'ville' => $source->getName(),
                'id' => $source->getId()
            );
        }

        // ...avant de les retourner en json
        return new JsonResponse($data
        );
    }

    public function searchVilleAction(Request $request,$term)
    {


        $serializer = SerializerBuilder::create()->build();

        $em = $this->getDoctrine()->getManager();

        $dql = "SELECT v FROM UlostVilleBundle:Ville v WHERE v.name LIKE '" . $term . "%' ORDER BY v.name ASC";
        $query = $em->createQuery($dql);
        $result = $query
            ->setMaxResults(10)
            ->getArrayResult();

        return $listVilles=$result;
    }


    public function villeSuggestAction(Request $request)
    {
        $query = $request->get('search', null);

        // notre index est directement disponible sous forme de service
        $index = $this->container->get('fos_elastica.index.ulost.ville');

        $searchQuery = new \Elastica\Query\QueryString();
        $searchQuery->setParam('query', $query);

        // nous forçons l'opérateur de recherche à AND, car on veut les résultats qui
        // correspondent à tous les mots de la recherche, plutôt qu'à au moins un
        // d'entre eux (opérateur OR)
        $searchQuery->setDefaultOperator('AND');

        // on exécute une requête de type "fields", qui portera sur les colonnes "name"
        // et "codePostal" de l'index
        $searchQuery->setParam('fields', array(
            'name',
            'codePostal',
        ));

        // exécution de la requête, limitée aux 10 premiers résultats
        $results = $index->search($searchQuery, 10)->getResults();

        $data = array();

        // on arrange les données des résultats...
        foreach ($results as $result) {
            $source = $result->getSource();
            $data[] = array(
                'suggest'   => $source['codePostal'].' '.$source['name'],
                'codePostal'   => $source['codePostal'],
                'ville'      => $source['name'],
                'id' => $source['id']
            );
        }

        // ...avant de les retourner en json
        return new JsonResponse($data, 200, array(
            'Cache-Control' => 'no-cache',
        ));
    }



    public function indexVilleByAgglomerationAction(Request $request,$id)
    {

        $em = $this->getDoctrine()->getManager();
        $agglomeration=$em->getRepository('UlostVilleBundle:Agglomeration')->find($id);
        $listVille = $em
            ->getRepository('UlostVilleBundle:Ville')
            ->findByAgglomeration($agglomeration, array('name' => 'ASC'));
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $listVille,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        if (null === $listVille) {
            throw new NotFoundHttpException("Il n'y a pas de ville à afficher");
        }

        return $this->render('UlostVilleBundle:Ville:indexVille.html.twig', array(
            'pagination' => $pagination
        ));

    }
}
