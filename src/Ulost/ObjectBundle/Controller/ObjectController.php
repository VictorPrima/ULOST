<?php

namespace Ulost\ObjectBundle\Controller;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Ulost\ObjectBundle\Entity\Object;
use Ulost\ObjectBundle\Entity\Category;
use Ulost\ObjectBundle\Entity\Question;
use Ulost\ObjectBundle\Entity\Alternative;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Ulost\ObjectBundle\Form\CategoryType;
use Ulost\ObjectBundle\Form\ObjectType;
use Ulost\ObjectBundle\Form\QuestionAlternativeType;
use Ulost\ObjectBundle\Form\QuestionType;
use Ulost\ObjectBundle\Form\AlternativeType;
use JMS\Serializer\SerializerBuilder;
use Doctrine\Common\Collections\ArrayCollection;


class ObjectController extends Controller
{

    public function addObjectAction(Request $request, $id)
    {
        $category = $this->getDoctrine()->getRepository('UlostObjectBundle:Category')->find($id);
        $object = new Object();
        $object->setCategory($category);
        $form = $this->get('form.factory')->create(ObjectType::class, $object);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice',
                'Le type d\'objet ' . $object->getTypeObjet() . ' a bien été ajouté');

            $request->getSession()->getFlashBag()->add('notice',
                ' dans la Catégorie' . $object->getCategory()->getName());
            return $this->redirectToRoute("ulost_view_object", array('id'=>$object->getId()));
        }

        return $this->render('UlostObjectBundle:Advert:addObject.html.twig', array(
                'form' => $form->createView(), 'category' => $category
            )
        );
    }


    public function addCategoryAction(Request $request)
    {

        // Création de l'entité category
        $category = new Category();


        $form = $this->get('form.factory')->create(CategoryType::class, $category);


        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();


            $request->getSession()->getFlashBag()->add('notice',
                'La catégorie ' . $category->getName() . ' a bien été ajoutée');


        }
        return $this->render('UlostObjectBundle:Advert:addCategory.html.twig', array(
                'form' => $form->createView(),
            )
        );
    }


    public function indexCategoryAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $listCategory = $em
            ->getRepository('UlostObjectBundle:Category')
            ->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $listCategory,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );
        if (null === $listCategory) {
            throw new NotFoundHttpException("Il n'y a pas de Catégories à afficher");
        }

        return $this->render('UlostObjectBundle:Advert:indexCategory.html.twig', array('pagination' => $pagination));
    }

    public function indexObjectByCategoryAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $listObject = $em
            ->getRepository('UlostObjectBundle:Object')
            ->findByCategory($id);

        if (null === $listObject) {
            throw new NotFoundHttpException("Il n'y a pas d'objet à afficher");
        }

        return $this->render('UlostObjectBundle:Advert:indexObject.html.twig', array(
            'listObject' => $listObject, 'id' => $id
        ));

    }


    public function indexQuestionByObjectAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $listQuestion = $em
            ->getRepository('UlostObjectBundle:Question')
            ->findByObject($id, array('ordre' => 'ASC'));


        if (null === $listQuestion) {
            throw new NotFoundHttpException("Il n'y a pas d'objet à afficher");
        }

        return $this->render('UlostObjectBundle:Advert:indexQuestion.html.twig', array(
            'listQuestion' => $listQuestion
        ));

    }


    public function viewObjectAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $object = $em
            ->getRepository('UlostObjectBundle:Object')
            ->find($id);

        if (null === $object) {
            throw new NotFoundHttpException("Il n'y a pas d'objet à afficher");
        }
        $image = $object->getImage();

        return $this->render('UlostObjectBundle:Advert:viewObject.html.twig', array(
            'object' => $object, 'image' => $image
        ));

    }

    public function editAlternativeAction($id, Request $request)
    {
        if (!$id) {
            throw $this->createNotFoundException('L\'alternative ' . $id . 'n\'existe pas');
        }
        $alternative = $this->getDoctrine()->getRepository('UlostObjectBundle:Alternative')->find($id);
        if (!$alternative) {
            throw $this->createNotFoundException('L\'alternative ' . $id . 'n\'existe pas');
        }

        $form = $this->get('form.factory')->create(AlternativeType::class, $alternative);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($alternative);
            $em->flush();


            $request->getSession()->getFlashBag()->add('notice', 'L\'alternative ' . $alternative->getId() . ' a été modifiée');

        }

        return $this->render('UlostObjectBundle:Advert:editAlternative.html.twig', array(
                'form' => $form->createView(), 'alternative' => $alternative
            )
        );
    }


    public function addQuestionAction(Request $request, $id)
    {
        $object = $this->getDoctrine()->getRepository('UlostObjectBundle:Object')->find($id);
        $question = new Question();
        $question->setObject($object);
        $form = $this->get('form.factory')->create(QuestionType::class, $question);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();


            $request->getSession()->getFlashBag()->add('notice',
                'La question ' . $question->getName() . ' a bien été ajoutée pour l\'objet ' . $object->getTypeObjet());

            if ($question->getTypeQuestion() == "option" || $question->getTypeQuestion() == "multipleChoice") {
                return $this->redirectToRoute("ulost_edit_question", array('id' => $question->getId()));
            }
            return $this->redirectToRoute('ulost_view_object', array(
                'id' => $question->getObject()->getId()));
            // $request->getSession()->getFlashBag()->add('notice',    $question->getObject()->getTypeObject() );
        }

        return $this->render('UlostObjectBundle:Advert:addQuestion.html.twig', array(
                'form' => $form->createView(), 'object' => $object, 'question'=>$question
            )
        );
    }


    public function editQuestionAction(Request $request, $id)
    {
        if (!$id) {
            throw $this->createNotFoundException('La question ' . $id . 'n\'existe pas');
        }
        $question = $this->getDoctrine()->getRepository('UlostObjectBundle:Question')->find($id);
        if (!$question) {
            throw $this->createNotFoundException('La question ' . $id . 'n\'existe pas');
        }

        $originalType = $question->getTypeQuestion();
        if ($originalType == "option") {
            $listOriginalAlternatives = $question->getAlternatives();
            if (!empty($listOriginalAlternatives)) {
                $arrayOriginalAlternatives = new ArrayCollection();
                foreach ($listOriginalAlternatives as $originalAlternative) {
                    $arrayOriginalAlternatives->add($originalAlternative);
                }
            }
        }

        $form = $this->get('form.factory')->create(QuestionType::class, $question);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if ($question->getTypeQuestion() == 'option' && !empty($arrayOriginalAlternatives)) {
                foreach ($arrayOriginalAlternatives as $originalAlternative) {

                    if (in_array($originalAlternative, $question->getAlternatives()->toArray()) === false) {

                        $question->removeAlternative($originalAlternative);
                        $em->remove($originalAlternative);
                        $em->flush();
                    }
                }
            }
            $em->persist($question);
            $em->flush();


            if ($originalType != 'option' && $question->getTypeQuestion() == "option") {
                $request->getSession()->getFlashBag()->add('notice', "Le type de la question '" . $question->getName() . "' a été modifié. Veuillez rentrer des alternatives.");
                return $this->redirectToRoute('ulost_edit_question', array(
                        'id' => $id)
                );
            }
            $request->getSession()->getFlashBag()->add('notice', "La question '" . $question->getName() . "' a été modifiée");
            return $this->redirectToRoute('ulost_view_object', array(
                'id' => $question->getObject()->getId()));

        }

        return $this->render('UlostObjectBundle:Advert:editQuestion.html.twig', array(
                'form' => $form->createView(), 'question' => $question, 'object' => $question->getObject()
            )
        );
    }


    public function removeQuestionAction($id)
    {
        if (!$id) {
            throw $this->createNotFoundException('La question ' . $id . ' n\'existe pas');
        }
        $em = $this->getDoctrine()->getEntityManager();
        $question = $em->getRepository('UlostObjectBundle:Question')->find($id);
        if (!$question) {
            throw $this->createNotFoundException('La question ' . $id . ' n\'existe pas');
        }
        $object = $question->getObject();
        $em->remove($question);
        $em->flush();

        return $this->redirect($this->generateUrl('ulost_view_object', array('id' => $object->getId())));

    }


    public function editObjectAction($id, Request $request)
    {
        if (!$id) {
            throw $this->createNotFoundException('L\'objet ' . $id . 'n\'existe pas');
        }
        $object = $this->getDoctrine()->getRepository('UlostObjectBundle:Object')->find($id);
        if (!$object) {
            throw $this->createNotFoundException('L\'objet ' . $id . 'n\'existe pas');
        }

        $form = $this->get('form.factory')->create(ObjectType::class, $object);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
           $object->getImage()->upload();
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();


            $request->getSession()->getFlashBag()->add('notice', 'L\'objet ' . $object->getId() . ' a été modifiée');
            return $this->redirectToRoute('ulost_view_object', array(
                'id' => $object->getId()
            ));
        }

        return $this->render('UlostObjectBundle:Advert:editObject.html.twig', array(
                'form' => $form->createView(), 'object' => $object
            )
        );
    }


    public function editCategoryAction($id, Request $request)
    {
        if (!$id) {
            throw $this->createNotFoundException('La catégorie ' . $id . 'n\'existe pas');
        }
        $category = $this->getDoctrine()->getRepository('UlostObjectBundle:Category')->find($id);
        if (!$category) {
            throw $this->createNotFoundException('La catégorie ' . $id . 'n\'existe pas');
        }

        $form = $this->get('form.factory')->create(CategoryType::class, $category);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();


            $request->getSession()->getFlashBag()->add('notice', 'La catégorie ' . $category->getName() . ' a été modifiée');

        }

        return $this->render('UlostObjectBundle:Advert:editCategory.html.twig', array(
                'form' => $form->createView(), 'category' => $category
            )
        );
    }


    public function removeCategoryAction($id)
    {
        if (!$id) {
            throw $this->createNotFoundException('La catégorie ' . $id . ' n\'existe pas');
        }
        $em = $this->getDoctrine()->getEntityManager();
        $category = $em->getRepository('UlostObjectBundle:Category')->find($id);
        if (!$category) {
            throw $this->createNotFoundException('L\'objet ' . $id . 'n\'existe pas');
        }
        $em->remove($category);
        $em->flush();

        return $this->redirect($this->generateUrl('ulost_home'));

    }

    public function removeObjectAction($id)
    {
        if (!$id) {
            throw $this->createNotFoundException('L\'objet ' . $id . ' n\'existe pas');
        }
        $em = $this->getDoctrine()->getEntityManager();
        $object = $em->getRepository('UlostObjectBundle:Object')->find($id);
        if (!$object) {
            throw $this->createNotFoundException('L\'objet ' . $id . 'n\'existe pas');
        }
        $em->remove($object);
        $em->flush();

        return $this->redirectToRoute("ulost_index_category");

    }

    public function removeAlternativeAction($id)
    {
        if (!$id) {
            throw $this->createNotFoundException('L\'option ' . $id . ' n\'existe pas');
        }
        $em = $this->getDoctrine()->getEntityManager();
        $alternative = $em->getRepository('UlostObjectBundle:Alternative')->find($id);
        if (!$alternative) {
            throw $this->createNotFoundException('L\'option ' . $id . 'n\'existe pas');
        }
        $object = $alternative->getQuestion()->getObject();
        $em->remove($alternative);
        $em->flush();

        return $this->redirect($this->generateUrl('ulost_view_object', array('id' => $object->getId())));

    }

    public function showImageByObjectAction($object_id)
    {

        $object = $this->getDoctrine()->getRepository('UlostObjectBundle:Object')->find($object_id);
        $image = $object->getImage();

        if ($image) {


            return $this->render('UlostObjectBundle:Advert:showImage.html.twig', array(
                    'image' => $image, 'object' => $object
                )
            );
        } else {
            return $this->render('UlostObjectBundle:Advert:imageIntrouvable.html.twig');
        }
    }

    public function addAlternativeAction(Request $request, $id)
    {
        $question = $this->getDoctrine()->getRepository('UlostObjectBundle:Question')->find($id);
        $alternative = new Alternative();
        $alternative->setQuestion($question);
        $form = $this->get('form.factory')->create(AlternativeType::class, $alternative);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($alternative);
            $em->flush();


            $request->getSession()->getFlashBag()->add('notice',
                'La réponse ' . $alternative->getName() . ' a bien été ajoutée pour la question ' . $question->getName());

        }

        return $this->render('UlostObjectBundle:Advert:addAlternative.html.twig', array(
                'form' => $form->createView(), 'question' => $question
            )
        );
    }

    public function indexAlternativeByQuestionAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $listAlternatives = $em
            ->getRepository('UlostObjectBundle:Alternative')
            ->findByQuestion($id);


        if (null === $listAlternatives) {
            throw new NotFoundHttpException("Il n'y a pas d'objet à afficher");
        }

        return $this->render('UlostObjectBundle:Advert:indexAlternative.html.twig', array(
            'listAlternatives' => $listAlternatives
        ));


    }


    public function showAlternativeAction($id)
    {
        $question = $this->getDoctrine()->getRepository('UlostObjectBundle:Question')->find($id);

        $listAlternatives = $question->getAlternatives();


        if (null === $listAlternatives) {
            throw new NotFoundHttpException("Il n'y a pas de questions à afficher");
        }

        return $this->render('UlostObjectBundle:Advert:showAlternative.html.twig', array(
            'listAlternatives' => $listAlternatives, 'question' => $question
        ));


    }


}