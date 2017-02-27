<?php

namespace Ulost\AnnonceBundle\Controller;


use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ulost\AnnonceBundle\Entity\Annonce;
use Ulost\AnnonceBundle\Entity\Reponse;
use Ulost\AnnonceBundle\Form\AnnonceType;
use Ulost\AnnonceBundle\Form\ReponseType;
use Ulost\ObjectBundle\Entity\Category;
use Ulost\ObjectBundle\Entity\Object;
use Ulost\VilleBundle\Entity\Ville;
use Symfony\Component\HttpFoundation\JsonResponse;


class PostController extends Controller
{


    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        $session->clear();
        return $this->render('UlostAnnonceBundle:Post:testindex.html.twig');
    }


    public function indexObjectAction(Request $request, $status)
    {
        $session = $request->getSession();
        $session->clear();
        $session->set('status', $status);

        $em = $this->getDoctrine()->getManager();
        $listCategory = $em
            ->getRepository('UlostObjectBundle:Category')
            ->findAll();
        return $this->render('UlostAnnonceBundle:Post:indexCategory.html.twig', array('status' => $status, 'listCategory' => $listCategory));
    }


    public function indexObjectByCategoryAction($id, $status)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('UlostObjectBundle:Category')->find($id);
        $listObject = $em
            ->getRepository('UlostObjectBundle:Object')
            ->findByCategory($id);

        if (null === $listObject) {
            throw new NotFoundHttpException("Il n'y a pas d'objet à afficher");
        }

        return $this->render('UlostAnnonceBundle:Post:indexObject.html.twig', array(
            'listObject' => $listObject, 'id' => $id, 'status' => $status, 'category' => $category
        ));

    }

    public function showQuestionsAction($object_id)
    {
        $em = $this->getDoctrine()->getManager();
        $listQuestion = $em
            ->getRepository('UlostObjectBundle:Question')
            ->findByObject($object_id, array('ordre' => 'ASC'));


        if (null === $listQuestion) {
            throw new NotFoundHttpException("Il n'y a pas de questions à afficher");
        }

        return $this->render('UlostAnnonceBundle:Post:indexQuestions.html.twig', array(
            'listQuestion' => $listQuestion
        ));

    }

    public function newAnnonceBisAction(Request $request, $status)
    {
        $object_id = $request->get('objectId');
        return $this->redirectToRoute("ulost_annonce_newAnnonce", array('status' => $status, 'object_id' => $object_id));
    }

    public function newAnnonceAction(Request $request, $status, $object_id)
    {
        $object = $this->getDoctrine()->getRepository("UlostObjectBundle:Object")->find($object_id);
        $session = $request->getSession();

        if (!$this->getUser()) {
            $user = $this->getDoctrine()->getRepository("UlostUserBundle:User")->find(1);
        } else {
            $user = $this->getUser();
        }

        $annonce = new Annonce();
        $annonce->setUser($user);
        $annonce->setstatus($status);
        $annonce->setObject($object);
        if ($session->has('annonceParent')) {
            $annonceParent = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->find($session->get('annonceParent'));
            $annonce->setParent($annonceParent);
            $annonce->setVille($annonceParent->getVille());
        }
        $listQuestions = $this->getDoctrine()->getRepository('UlostObjectBundle:Question')->findByObject(
            array('object' => $object),
            array('ordre' => 'ASC')
        );

        foreach ($listQuestions as $question) {
            $reponse = new Reponse();
            $reponse->setQuestion($question);
            $annonce->addReponse($reponse);
        }


        $form = $this->createForm(AnnonceType::class, $annonce);


        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $annonce->setDate(new \Datetime());
            $annonce->setPublished(false);
            $annonce->setArchived(false);
            $this->getDoctrine()->getManager()->persist($annonce);
            $this->getDoctrine()->getManager()->flush();
            $session->set('annonce_id', $annonce->getId());


            return $this->redirectToRoute('ulost_lieu_annonce');


        } else {
            return $this->render('UlostAnnonceBundle:Post:newAnnonce.html.twig', array(
                'form' => $form->createView(), 'object' => $object, 'status' => $status
            ));
        }
    }


    public function lieuAction(Request $request)
    {

        $session = $request->getSession();
        if ($annonce_id = $session->get('annonce_id')) {
            if ($annonce = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->find($annonce_id)) {


                if ($session->has('service')) {
                    $service = $session->get('service');
                    return $this->get('StockAction')->recapStockAction($request, $annonce, $service);

                } elseif ($annonce->getParent() != null) {
                    return $this->redirectToRoute('ulost_annonce_enregistrer', array('annonce_id' => $annonce_id));
                }


                return $this->render('UlostAnnonceBundle:Post:lieuAnnonce.html.twig', array(
                    'annonce' => $annonce
                ));
            }
        }


        $request->getSession()->getFlashBag()->add('notice',
            'Vous n\'avez pas encore rempli d\'annonce !');
        return $this->render('UlostCoreBundle:Home:index.html.twig');

    }

    public function recapAction(Request $request)
    {
        $session = $request->getSession();
        if ($request->getMethod() == 'POST') {
            $annonce_id = $session->get('annonce_id');
            $villeId = $request->get('villeId');
            $ville = $this->getDoctrine()->getRepository('UlostVilleBundle:Ville')->find($villeId);
            $annonce = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->find($annonce_id);
            $annonce->setVille($ville);
            $this->getDoctrine()->getManager()->persist($annonce);
            $this->getDoctrine()->getManager()->flush();

            if (!$this->getUser()) {
                return $this->redirectToRoute("ulost_user_login");
            } else {
                return $this->redirectToRoute('ulost_annonce_enregistrer', array('annonce_id' => $annonce_id));
            }

        } else {
            $request->getSession()->getFlashBag()->add('notice',
                'Il y a eu un problème');
            return $this->redirectToRoute('ulost_home');

        }
    }

    public function enregistrerAction(Request $request, $annonce_id)
    {
        $annonce = $this->getDoctrine()->getRepository('UlostAnnonceBundle:Annonce')->find($annonce_id);
        $annonce->setUser($this->getUser());
        $annonce->setPublished(true);
        $this->getDoctrine()->getManager()->persist($annonce);
        $this->getDoctrine()->getManager()->flush();
        {
            $user = $annonce->getUser();
            $subject = "Annonce postée : " . $annonce->getObject()->getTypeObjet();
            $url = $this->generateUrl('ulost_oneannoncepage', array('id' => $annonce_id), UrlGeneratorInterface::ABSOLUTE_URL);
            $body = "Bonjour " . $user->getUsername() . ", votre annonce concernant " . $annonce->getObject()->getTypeObjet() . $annonce->getStatus() .
                " a bien été postée. Vous pouvez la retrouver à cette adresse :" . $url . "
                Pensez à revenir régulièrement sur ulost.fr pour vérifier si des correspondances ont été trouvées.
                Cordialement";
            $this->get('MailAction')->sendEmailAction($user, $subject, $body);

            {
                $request->getSession()->getFlashBag()->add('notice',
                    'L\'annonce a bien été ajoutée');
            }
        }
        $this->get("AnnonceAction")->removeAllAnnonceFromUserAction($request, $this->getDoctrine()->getRepository("UlostUserBundle:User")->find(1));
        return $this->render("UlostAnnonceBundle:Post:enregistrement.html.twig", array(
            'annonce' => $annonce
        ));

    }

    public function addAnnonceEnfantAction(Request $request, $id)
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

    public function redirectAfterRegisterAction(Request $request)
    {
        $session = $request->getSession();
        if ($session->has('annonce_id')) {
            return $this->render("UlostAnnonceBundle:Post:redirectAfterRegister.html.twig", array(
                'annonce_id' => $session->get('annonce_id')
            ));
        } else {
            return $this->render("UlostUserBundle:Authentification:retourAccueil.html.twig");
        }
    }

    public function showCategoryAction(Category $category, $status)
    {
        return $this->render("UlostAnnonceBundle:Post:showCategory.html.twig", array(
            'category' => $category,
            'status' => $status
        ));
    }

    public function showObjectAction(Object $object, $status)
    {
        return $this->render("UlostAnnonceBundle:Post:showObject.html.twig", array(
            'object' => $object,
            'status' => $status
        ));
    }

    public function showMenuObjetAction(Request $request)
    {
        $session = $request->getSession();
        $status = $session->get('status');
        return $this->render("UlostAnnonceBundle:Post:menuObjet.html.twig", array(
            'status' => $status
        ));
    }

    public function showSearchObjectAction()
    {
        return $this->render("UlostAnnonceBundle:Post:searchObjet.html.twig");
    }


    public function searchObjectAction(Request $request)
    {

        $term = $request->get('search', null);


        $em = $this->getDoctrine()->getManager();

        $dql = "SELECT o FROM UlostObjectBundle:Object o WHERE o.typeObjet LIKE '%" . $term . "%' ORDER BY o.typeObjet ASC";
        $query = $em->createQuery($dql);
        $results = $query->getResult();

        $data = array();

        // on arrange les données des résultats...
        foreach ($results as $source) {

            $data[] = array(
                'suggest' => $source->getTypeObjet() . ' (' . $source->getCategory()->getName().')',
                'category' => $source->getCategory()->getName(),
                'object' => $source->getTypeObjet(),
                'id' => $source->getId()
            );
        }

        // ...avant de les retourner en json
        return new JsonResponse($data);
    }




}