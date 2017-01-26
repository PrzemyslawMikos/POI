<?php

namespace PoiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PoiBundle\Entity\Ratings;
use PoiBundle\Form\RatingsType;

class RatingsController extends Controller
{
    /**
     * Lists all Ratings entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ratings = $em->getRepository('PoiBundle:Ratings')->findAll();

        return $this->render('ratings/index.html.twig', array(
            'ratings' => $ratings,
        ));
    }

    /**
     * Creates a new Ratings entity.
     *
     */
    public function newAction(Request $request)
    {
        $rating = new Ratings();
        $form = $this->createForm('PoiBundle\Form\RatingsType', $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($rating);
            $em->flush();

            return $this->redirectToRoute('ratings_show', array('id' => $rating->getId()));
        }

        return $this->render('ratings/new.html.twig', array(
            'rating' => $rating,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Ratings entity.
     *
     */
    public function showAction(Ratings $rating)
    {
        $deleteForm = $this->createDeleteForm($rating);

        return $this->render('ratings/show.html.twig', array(
            'rating' => $rating,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Ratings entity.
     *
     */
    public function editAction(Request $request, Ratings $rating)
    {
        $deleteForm = $this->createDeleteForm($rating);
        $editForm = $this->createForm('PoiBundle\Form\RatingsType', $rating);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($rating);
            $em->flush();

            return $this->redirectToRoute('ratings_edit', array('id' => $rating->getId()));
        }

        return $this->render('ratings/edit.html.twig', array(
            'rating' => $rating,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Ratings entity.
     *
     */
    public function deleteAction(Request $request, Ratings $rating)
    {
        $form = $this->createDeleteForm($rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($rating);
            $em->flush();
        }

        return $this->redirectToRoute('ratings_index');
    }

    /**
     * Creates a form to delete a Ratings entity.
     *
     * @param Ratings $rating The Ratings entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Ratings $rating)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ratings_delete', array('id' => $rating->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
