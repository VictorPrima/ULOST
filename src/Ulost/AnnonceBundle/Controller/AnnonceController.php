<?php

namespace Ulost\AnnonceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ulost\AnnonceBundle\Entity\Annonce;
use Ulost\AnnonceBundle\Entity\Reponse;
use Ulost\AnnonceBundle\Repository\AnnonceRepository;
use Ulost\MunicipaleBundle\Entity\Service;
use Ulost\ObjectBundle\Entity\Object;
use Ulost\UserBundle\Entity\User;
use Ulost\VilleBundle\Entity\Ville;
use Doctrine\ORM\QueryBuilder;


class AnnonceController extends Controller
{


    public function indexAction()
    {
        return $this->render('UlostCoreBundle:Objet:index.html.twig');
    }


    public function indexByVilleAction(Request $request, $ville_id)
    {
        $ville = $this->getDoctrine()->getRepository('UlostVilleBundle:Ville')->find($ville_id);
        return $this->showAllAnnonceByVilleAction($request, $ville);
    }


    public function indexUserAction(Request $request, $page)
    {
        $user = $this->getUser();
        return $this->indexByUserAction($request, $user->getId(), $page);
    }

    public function indexHistoriqueAction(Request $request, $page)
    {
        $user = $this->getUser();
        return $this->indexHistoriqueByUserAction($request, $user->getId(), $page);
    }


    public function indexByTypeAction(Request $request, $type_id)
    {

        $object = $this->getDoctrine()->getRepository('UlostObjectBundle:Object')->find($type_id);
        return $this->showAllAnnonceByObject($request, $object);


    }

    public function indexEnfantByAnnonceAction(Request $request, $id)
    {

        $annonce = $this->getDoctrine()->getManager()
            ->getRepository('UlostAnnonceBundle:Annonce')->find($id);
        return $this->showByAnnonceParent($request, $annonce);


    }

    /**
     * Retourne la liste complète des annonces
     */
    public function indexAllAnnonceAction(Request $request, $page)
    {

        $query = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->getAllAnnonce();
        if ($request->query->getAlnum('filter')) {
            $query->where('a.status LIKE :status')
                ->where('a.published = true')
                ->setParameter('status', '%' . $request->query->getAlnum('filter') . '%');
        }
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page)/*page number*/,
            50/*limit per page*/
        );

        return $this->render('UlostAnnonceBundle:Advert:indexall.html.twig',
            array('pagination' => $pagination
            ));
    }

    public function showAllAnnoncePublishedAction(Request $request)
    {
        $query = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->getAllAnnoncePublished();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            50/*limit per page*/
        );

        return $this->render('UlostAnnonceBundle:Advert:showall.html.twig',
            array('pagination' => $pagination
            ));
    }

    public function showByServiceAction(Request $request, Service $service)
    {
        $query = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->getAllAnnonceByService($service);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            50/*limit per page*/
        );

        return $this->render('UlostAnnonceBundle:Advert:indexByService.html.twig',
            array('pagination' => $pagination, 'service' => $service
            ));
    }


    public function nbAnnonceByServiceAction(Request $request, Service $service)
    {
        $nbAnnonces = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->countAllAnnonceByService($service);
        return $this->render('UlostMunicipaleBundle:Home:showNbAnnonces.html.twig', array(
            'nbAnnonces' => $nbAnnonces
        ));
    }

    /**
     * @param $annonce
     * @return username associé
     */
    public function getUserNameFromAnnonce($annonce)
    {
        $id_user = $annonce->getUser();
        return $userName = $this->get('UserAction')->getNameUser($id_user);
    }


    /**
     * @param $ville
     * @return Liste de toutes les annonces d'une ville
     */
    public function showAllAnnonceByVilleAction(Request $request, Ville $ville)
    {

        $query = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->getAllAnnoncebyVille($ville);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            50/*limit per page*/

        );

        return $this->render('UlostAnnonceBundle:Advert:indexByVille.html.twig',
            array('pagination' => $pagination, 'ville' => $ville
            ));
    }


    /**
     * @param $user
     * @return Liste de toutes les annonces d'un utilisateur
     */
    public function indexByUserAction(Request $request, $user_id, $page)
    {
        $user = $this->getDoctrine()->getRepository('UlostUserBundle:User')->find($user_id);

        $query = $this->getDoctrine()
            ->getRepository('UlostAnnonceBundle:Annonce')
            ->getAllAnnoncePublishedbyUser($user);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->get('page', $page)/*page number*/,
            50/*limit per page*/

        );

        return $this->render('UlostAnnonceBundle:Advert:indexByUser.html.twig',
            array('pagination' => $pagination, 'user' => $user
            ));


    }

    public function indexHistoriqueByUserAction(Request $request, $user_id, $page)
    {
        $user = $this->getDoctrine()->getRepository('UlostUserBundle:User')->find($user_id);

        $query = $this->getDoctrine()
            ->getRepository('UlostAnnonceBundle:Annonce')
            ->getAllAnnonceArchivedbyUser($user);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->get('page', $page)/*page number*/,
            50/*limit per page*/

        );

        return $this->render('UlostAnnonceBundle:Advert:indexByUser.html.twig',
            array('pagination' => $pagination, 'user' => $user
            ));


    }


    /**
     * @param $type_id
     * @return Liste de toutes les annonces d'un certain type
     */


    public function showAllAnnonceByObject(Request $request, Object $object)
    {

        $query = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->getAllAnnoncebyObject($object);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            50/*limit per page*/

        );

        return $this->render('UlostAnnonceBundle:Advert:indexByObject.html.twig',
            array('pagination' => $pagination, 'object' => $object
            ));
    }

    /**
     * @param $id
     * @return Informations d'une annonce
     */
    public function showOneAction($id)
    {
        $annonce = $this->getDoctrine()->getManager()
            ->getRepository('UlostAnnonceBundle:Annonce')->find($id);

        $this->denyAccessUnlessGranted('view', $annonce);


        $userName = $this->getUserNameFromAnnonce($annonce);

        $object = $annonce->getObject();


        $type_objet = $object->getTypeObjet();
        $listQuestions = $this->getDoctrine()->getRepository('UlostObjectBundle:Question')->findByObject(
            array('object' => $object),
            array('ordre' => 'ASC'));


        return $this->render('UlostAnnonceBundle:Advert:indexone.html.twig', array(
            'annonce' => $annonce,
            'type_objet' => $type_objet,
            'listQuestions' => $listQuestions,
            'username' => $userName,

        ));

    }

    /**
     * @param $annonce_id
     * @return Metadonnées d'une annonce
     */
    public function showMetadonneesAction($annonce_id)
    {

        $annonce = $this->getDoctrine()->getManager()
            ->getRepository('UlostAnnonceBundle:Annonce')->find($annonce_id);

        return $this->render('UlostAnnonceBundle:Advert:showMeta.html.twig',
            array('annonce' => $annonce));
    }


    /**
     * @param id d'une annonce
     * @return type de l'annonce
     */
    public function getTypeFromAnnonce($id)
    {
        $annonce = $this->getDoctrine()->getManager()
            ->getRepository('UlostAnnonceBundle:Annonce')->find($id);
        $object = $annonce->getObject();
        return $type_objet = $object->getTypeObjet();
    }


    public function getVilleFromAnnonce($id)
    {
        $annonce = $this->getDoctrine()->getManager()
            ->getRepository('UlostAnnonceBundle:Annonce')->find($id);

        $nom_ville = $annonce->getVille();
        return $nom_ville;
    }

    public function getTypeIdFromAnnonce($id)
    {
        $annonce = $this->getDoctrine()->getManager()
            ->getRepository('UlostAnnonceBundle:Annonce')->find($id);
        $id_objet = $annonce->getObject();
        return $id_objet;
    }

    public function IsTrouvePerduFromAnnonce($id)
    {
        $annonce = $this->getDoctrine()->getManager()
            ->getRepository('UlostAnnonceBundle:Annonce')->find($id);
        $status = $annonce->getStatus();
        return $status;
    }


    public function getCorrespondance1($id)
    {
        $type_id = $this->getTypeIdFromAnnonce($id);
        $ville = $this->getVilleFromAnnonce($id);
        $status = $this->IsTrouvePerduFromAnnonce($id);


        $list_annonce = $this->getDoctrine()->getManager()
            ->getRepository('UlostAnnonceBundle:Annonce')->findAllByTypeAndVille($type_id, $ville, $status);

        return $list_annonce;
    }

    public function showReponseByAnnonceAction(Annonce $annonce)
    {
        $listReponses = $annonce->getReponses();
        return $this->render('UlostAnnonceBundle:Advert:showReponses.html.twig',
            array('annonce' => $annonce, 'listReponses' => $listReponses));

    }

    public function getChampReponseAction(Reponse $reponse)
    {
        $question = $reponse->getQuestion();
        if ($question->getTypeQuestion() == "option") {
            $listAlternatives = $question->getAlternatives();
            if ($listAlternatives[$reponse->getChamp()] != null) {
                $champReponse = $listAlternatives[$reponse->getChamp()]->getName();
            } else {
                $champReponse = "Pas de réponse donnée";
            }
        } elseif ($question->getTypeQuestion() == "checkbox") {
            if ($reponse->getChamp() == 1) {
                $champReponse = "Oui";
            } else $champReponse = "Non";
        } else {
            $champReponse = $reponse->getChamp();
        }

        return $champReponse;
    }


    public function showReponseAction(Reponse $reponse)
    {
        $champReponse = $this->getChampReponseAction($reponse);


        return $this->render('UlostAnnonceBundle:Advert:showChampReponse.html.twig',
            array('champReponse' => $champReponse));

    }

    public function removeOneAnnonceAction(Request $request, $id)
    {
        $annonce = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->find($id);
        if ($this->removeAnnonceAction($request, $annonce)) {
            return $this->redirectToRoute('ulost_home');
        } else {

            return $this->viewAnnonceAction($annonce);
        }
    }


    public function removeAllAnnonceAction(Request $request){
        $listUser = $this->getDoctrine()->getRepository('UlostUserBundle:User')->findAll();
        foreach ($listUser as $user){
            try{
                $this->removeAllAnnonceFromUserAction($request, $user->getId());
            }
            catch(\Exception $e){
                error_log($e->getMessage());
            };
        }
        return $this->redirectToRoute('ulost_home');
    }


    public function removeAllAnnonceFromUserAction(Request $request, $id)
    {

        $user = $this->getDoctrine()->getRepository('UlostUserBundle:User')->find($id);

        $listAnnonces = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->findBy(array('user' => $user));
        foreach ($listAnnonces as $annonce) {

            $this->removeAnnonceAction($request, $annonce);
        }
        $request->getSession()->getFlashBag()->add('notice',
            'Toutes les annonces de l\'utilisateur ' . $user->getUsername() . ' ont bien été supprimées');
        return true;
    }


    public function removeAnnonceAction(Request $request, Annonce $annonce)
    {
        $id = $annonce->getId();
        $this->get('CorrespondanceAction')->removeAllCorrespondancesfromAnnonceAction($request, $annonce);
        $this->denyAccessUnlessGranted('edit', $annonce);
        $em = $this->getDoctrine()->getManager();
        $em->remove($annonce);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice',
            'L\'annonce ' . $id . ' a bien été supprimée');
        return true;
    }

    public function viewAnnonceAction(Annonce $annonce)
    {
        return $this->render('UlostAnnonceBundle:Advert:viewAnnonce.html.twig',
            array('annonce' => $annonce
            ));
    }


    public function showImageByAnnonceAction(Annonce $annonce)
    {

        $image = $annonce->getImageAnnonce();
        if (!$image) {
            throw $this->createNotFoundException('L\'annonce n\'a pas d\'image associée');
        }
        return $this->render('UlostAnnonceBundle:Advert:showImage.html.twig', array(
                'image' => $image, 'annonce' => $annonce
            )
        );
    }

    public function archiverAnnonceAction(Request $request, $id)
    {
        $annonce = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->find($id);
        if ($this->archiverAction($request, $annonce)) {
            return $this->redirectToRoute('ulost_user_annonce');
        } else {
            throw $this->createNotFoundException('L\'annonce n\a pas pu être archivée');
        }
    }

    public function archiverAction(Request $request, Annonce $annonce)
    {
        $annonce->setArchived(true);
        $annonce->setPublished(false);
        $annonce->setArchivedAt(new \DateTime());
        $this->get('CorrespondanceAction')->archiverAllCorrespondancesFromAnnonceAction($request, $annonce);
        $em = $this->getDoctrine()->getManager();
        $em->persist($annonce);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice',
            'L\'annonce ' . $annonce->getId() . ' a bien été archivée');
        return true;
    }


    public function showByAnnonceParentAction(Request $request, Annonce $annonce)
    {
        $query = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->getAllAnnonceEnfantByAnnonce($annonce);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            50/*limit per page*/
        );

        return $this->render('UlostAnnonceBundle:Advert:indexEnfantsByAnnonce.html.twig',
            array('pagination' => $pagination, 'annonceParent' => $annonce
            ));
    }

    public function showOwnerOfAnnonceAction(Annonce $annonce)
    {
        $user = $this->getUser();
        if ($annonce->getUser() == $user) {
            return $this->render('UlostUserBundle:Advert:showUserName.html.twig', array(
                'user' => $user
            ));
        } else
            return $this->render('UlostUserBundle:Advert:noAccessToUserName.html.twig');
    }


    public function projectFilters(QueryBuilder $qb, $key, $val)
    {
        switch ($key) {
            case 'a.published': {
                $qb->andWhere($qb->expr()->like('a.published', ':published'));
                $qb->setParameter('published', true);

                break;
            }
            case 'p.archived': {
                $qb->andWhere($qb->expr()->like('a.archived', ':archived'));
                $qb->setParameter('archived', true);

                break;
            }

            default:
                // Do not allow filtering by anything else
                throw new \Exception("filter not allowed");
            // You can also enable automatic filtering
            //$paramName = preg_replace('/[^A-z]/', '_', $key);
            //$qb->andWhere($qb->expr()->eq($key, ":$paramName"));
            //$qb->setParameter($paramName, $val);
        }
    }
}