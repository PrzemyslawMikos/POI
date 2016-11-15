<?php

namespace PoiBundle\Controller;

use PoiBundle\Entity\Points;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PoiBundle\Controller\VersionsController;
use PoiBundle\Entity\Changes;
use PoiBundle\Form\ChangesType;

/**
 * Changes controller.
 *
 */
class ChangesController extends Controller
{
    /**
     * Lists all Changes entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $changes = $em->getRepository('PoiBundle:Changes')->findAll();

        return $this->render('changes/index.html.twig', array(
            'changes' => $changes,
        ));
    }

    /**
     * Creates a new Changes entity.
     *
     */
    public function newAction(Request $request)
    {
        $change = new Changes();
        $form = $this->createForm('PoiBundle\Form\ChangesType', $change);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($change);
            $em->flush();

            return $this->redirectToRoute('changes_show', array('id' => $change->getId()));
        }

        return $this->render('changes/new.html.twig', array(
            'change' => $change,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Changes entity.
     *
     */
    public function showAction(Changes $change)
    {
        $deleteForm = $this->createDeleteForm($change);

        return $this->render('changes/show.html.twig', array(
            'change' => $change,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Changes entity.
     *
     */
    public function editAction(Request $request, Changes $change)
    {
        $deleteForm = $this->createDeleteForm($change);
        $editForm = $this->createForm('PoiBundle\Form\ChangesType', $change);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($change);
            $em->flush();

            return $this->redirectToRoute('changes_edit', array('id' => $change->getId()));
        }

        return $this->render('changes/edit.html.twig', array(
            'change' => $change,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Changes entity.
     *
     */
    public function deleteAction(Request $request, Changes $change)
    {
        $form = $this->createDeleteForm($change);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($change);
            $em->flush();
        }

        return $this->redirectToRoute('changes_index');
    }

    /**
     * Creates a form to delete a Changes entity.
     *
     * @param Changes $change The Changes entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Changes $change)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('changes_delete', array('id' => $change->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
