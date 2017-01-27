<?php

namespace PoiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PoiBundle\Entity\Warnings;
use PoiBundle\Form\WarningsType;
//TODO czo z tym??
class WarningsController extends Controller
{
    /**
     * Lists all Warnings entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $warnings = $em->getRepository('PoiBundle:Warnings')->findAll();

        return $this->render('warnings/index.html.twig', array(
            'warnings' => $warnings,
        ));
    }

    /**
     * Creates a new Warnings entity.
     *
     */
    public function newAction(Request $request)
    {
        $warning = new Warnings();
        $form = $this->createForm('PoiBundle\Form\WarningsType', $warning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($warning);
            $em->flush();

            return $this->redirectToRoute('warnings_show', array('id' => $warning->getId()));
        }

        return $this->render('warnings/new.html.twig', array(
            'warning' => $warning,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Warnings entity.
     *
     */
    public function showAction(Warnings $warning)
    {
        $deleteForm = $this->createDeleteForm($warning);

        return $this->render('warnings/show.html.twig', array(
            'warning' => $warning,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Warnings entity.
     *
     */
    public function editAction(Request $request, Warnings $warning)
    {
        $deleteForm = $this->createDeleteForm($warning);
        $editForm = $this->createForm('PoiBundle\Form\WarningsType', $warning);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($warning);
            $em->flush();

            return $this->redirectToRoute('warnings_edit', array('id' => $warning->getId()));
        }

        return $this->render('warnings/edit.html.twig', array(
            'warning' => $warning,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Warnings entity.
     *
     */
    public function deleteAction(Request $request, Warnings $warning)
    {
        $form = $this->createDeleteForm($warning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($warning);
            $em->flush();
        }

        return $this->redirectToRoute('warnings_index');
    }

    /**
     * Creates a form to delete a Warnings entity.
     *
     * @param Warnings $warning The Warnings entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Warnings $warning)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('warnings_delete', array('id' => $warning->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
