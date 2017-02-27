<?php
// src/Ulost/AdminBundle/Controller/ObjetController.php

namespace Ulost\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Ulost\CoreBundle\Form\ObjetType;
use Ulost\CoreBundle\Entity\Objet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObjetController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render('UlostCoreBundle:Objet:index.html.twig');
    }

    public function showObjetAction(Request $request)
    {
        $listobjet = $this->getDoctrine()->getManager()
            ->getRepository('UlostCoreBundle:Objet')->findAll();
        return $this->render('UlostCoreBundle:Objet:show.html.twig',
            array('listobjet' => $listobjet));
    }

    public function addObjetAction(Request $request)
    {
        $objet = new Objet;
        $form = $this->createForm(ObjetType::class, $objet);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $objet = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($objet);
            $em->flush();
            return $this->redirect($this->generateUrl('ulost_admin_objetpage'));

        }
        return $this->render('UlostCoreBundle:Objet:add.html.twig', array(
            'form' => $form->createView()));
    }

    public function indexoneAction($id)
    {
        return $this->render('UlostCoreBundle:Objet:indexone.html.twig', array(
            'id' => $id));
    }


    public function showOneAction($id)
    {
        $objet = $this->getDoctrine()->getManager()
            ->getRepository('UlostCoreBundle:Objet')->find($id);

        $listequestion = $objet->getQuestions();

        $list_id_question = explode("||", $listequestion);

        return $this->render('UlostCoreBundle:Objet:showall.html.twig',
            array('list_question' => $list_id_question, 'id' => $id));

    }

    public function showQuestionNameAction($id_question)
    {
        $question = $this->getDoctrine()->getManager()->getRepository('UlostCoreBundle:Questions')
            ->find($id_question);
        return $this->render('UlostCoreBundle:Objet:showQuestionName.html.twig',
            array('question' => $question));
    }


    public function showQuestionAllAction($id_question)
    {
        $question = $this->getDoctrine()->getManager()->getRepository('UlostCoreBundle:Questions')
            ->find($id_question);
        return $this->render('UlostCoreBundle:Objet:showQuestionAll.html.twig',
            array('question' => $question));
    }


    public function getNameObjet($id)
    {
        $objet = $this->getDoctrine()->getManager()
            ->getRepository('UlostCoreBundle:Objet')->find($id);
        $type_objet = $objet->getNom();
        return $type_objet;

    }
}