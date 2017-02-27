<?php

namespace Ulost\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class VilleController extends Controller
{
    public function found_villesAction($code_departement)
    {
        $villes = $this->getDoctrine()->getManager()
            ->getRepository('UlostCoreBundle:Villes')->findByVille_departement($code_departement);
        return $this->render('UlostCoreBundle:Advert:villes_2.html.twig',
            array('villes' => $villes));
    }

    public function VillesAction($cp)
    {
        $em = $this->getDoctrine()->getManager();
        $villeCodePostal = $em->getRepository('UlostCoreBundle:Villes')->findByVille_Code_Postal($cp);

        if ($villeCodePostal) {
            $ville = $villeCodePostal[0]->getVilleNomReel();
        } else {
            $ville = '';
        }

        $response = new JsonResponse();
        return $response->setData(array('ville' => $ville));
    }

    public function placeAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            $session = $request->getSession();

            if ($request->request->get('question1')) {
                $question1 = $request->request->get('question1');
                $session->set('question1', $question1);
            }
            if ($request->request->get('question2')) {
                $question2 = $request->request->get('question2');
                $session->set('question2', $question2);
            }
            if ($request->request->get('question3')) {
                $question3 = $request->request->get('question3');
                $session->set('question3', $question3);
            }
            if ($request->request->get('question4')) {
                $question4 = $request->request->get('question4');
                $session->set('question4', $question4);
            }
            if ($request->request->get('question5')) {
                $question5 = $request->request->get('question5');
                $session->set('question5', $question5);
            }
            if ($request->request->get('question6')) {
                $question6 = $request->request->get('question6');
                $session->set('question6', $question6);
            }
            if ($request->request->get('question7')) {
                $question7 = $request->request->get('question7');
                $session->set('question7', $question7);
            }
            if ($request->request->get('question8')) {
                $question8 = $request->request->get('question8');
                $session->set('question8', $question8);
            }
            if ($request->request->get('question9')) {
                $question9 = $request->request->get('question9');
                $session->set('question9', $question9);
            }
            if ($request->request->get('question10')) {
                $question10 = $request->request->get('question10');
                $session->set('question10', $question10);
            }
            if ($request->request->get('remarques')) {
                $remarques = $request->request->get('remarques');
                $session->set('remarques', $remarques);
            }
            return $this->render('UlostCoreBundle:Advert:place.html.twig');
        }
        $request->getSession()->getFlashBag()->add('notice', 'Pas de bétises !!');
        return $this->redirectToRoute('ulost_annonce_homepage');
    }

    public function place_detailsAction(Request $request, $place)
    {
        $keyword = $request->get('keyword');

        $session = $request->getSession();

        $region = $this->getDoctrine() ->getManager()
            ->getRepository('UlostCoreBundle:region')->findByNom($place);
        if (!$region)
        { throw $this->createNotFoundException("La région n'existe pas"); }
        else
        {
            $id_region=$region[0];
            $departements = $this->getDoctrine() ->getManager()
                ->getRepository('UlostCoreBundle:departement')->findByRegion($id_region);
            return $this->render('UlostCoreBundle:Advert:villes.html.twig',
                array('departements' => $departements));
        }
    }
}