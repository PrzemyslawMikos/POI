<?php

namespace PoiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use PoiBundle\Entity\Versions;
use PoiBundle\Form\VersionsType;

/**
 * Versions controller.
 *
 */
class VersionsController extends Controller
{
    /**
     * Lists all Versions entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $versions = $em->getRepository('PoiBundle:Versions')->findAll();

        return $this->render('versions/index.html.twig', array(
            'versions' => $versions,
        ));
    }

    /**
     * Creates a new Versions entity.
     *
     */
    public function newAction(Request $request)
    {
        $version = new Versions();
        $form = $this->createForm('PoiBundle\Form\VersionsType', $version);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($version);
            $em->flush();

            return $this->redirectToRoute('yes_show', array('id' => $version->getId()));
        }

        return $this->render('versions/new.html.twig', array(
            'version' => $version,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Versions entity.
     *
     */
    public function showAction(Versions $version)
    {
        $deleteForm = $this->createDeleteForm($version);

        return $this->render('versions/show.html.twig', array(
            'version' => $version,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Versions entity.
     *
     */
    public function editAction(Request $request, Versions $version)
    {
        $deleteForm = $this->createDeleteForm($version);
        $editForm = $this->createForm('PoiBundle\Form\VersionsType', $version);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($version);
            $em->flush();

            return $this->redirectToRoute('yes_edit', array('id' => $version->getId()));
        }

        return $this->render('versions/edit.html.twig', array(
            'version' => $version,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Versions entity.
     *
     */
    public function deleteAction(Request $request, Versions $version)
    {
        $form = $this->createDeleteForm($version);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($version);
            $em->flush();
        }

        return $this->redirectToRoute('yes_index');
    }

    /**
     * Creates a form to delete a Versions entity.
     *
     * @param Versions $version The Versions entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Versions $version)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('yes_delete', array('id' => $version->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
