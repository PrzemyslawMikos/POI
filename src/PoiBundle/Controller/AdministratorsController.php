<?php

namespace PoiBundle\Controller;

use PoiBundle\Additional\PaginationHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use PoiBundle\Entity\Administrators;
use PoiBundle\Form\AdministratorsType;

/**
 * Administrators controller.
 *
 */
class AdministratorsController extends Controller
{
    /**
     * Lists all Administrators entities.
     *
     */
    public function indexAction($page = 1)
    {
        $query = $this->getDoctrine()->getRepository('PoiBundle:Administrators')->findAllQuery();

        $paginationHelper = new PaginationHelper($query, $this->getParameter("administrators_index_elements"), $page);
        $paginationHelper->makePagination();

        return $this->render('administrators/index.html.twig', array(
            'administrators' => $paginationHelper,
            'page' => $page,
        ));
    }

    /**
     * Creates a new Administrators entity.
     *
     */
    public function newAction(Request $request)
    {
        $administrator = new Administrators();
        $form = $this->createForm('PoiBundle\Form\AdministratorsType', $administrator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($administrator);
            $em->flush();

            return $this->redirectToRoute('administrators_show', array('id' => $administrator->getId()));
        }

        return $this->render('administrators/new.html.twig', array(
            'administrator' => $administrator,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Administrators entity.
     *
     */
    public function showAction(Administrators $administrator)
    {
        $deleteForm = $this->createDeleteForm($administrator);

        return $this->render('administrators/show.html.twig', array(
            'administrator' => $administrator,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Administrators entity.
     *
     */
    public function editAction(Request $request, Administrators $administrator)
    {
        $deleteForm = $this->createDeleteForm($administrator);
        $editForm = $this->createForm('PoiBundle\Form\AdministratorsType', $administrator);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($administrator);
            $em->flush();

            return $this->redirectToRoute('administrators_edit', array('id' => $administrator->getId()));
        }

        return $this->render('administrators/edit.html.twig', array(
            'administrator' => $administrator,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Blocks selected administrator user
     *
     */
    public function blockAction(Administrators $administrator, $page){
        $user = $this->getUser();
        //You can't block yourself
        if($administrator->getId() === $user->getId()){
            $this->addFlash(
                'error',
                'You cant block yourself'
            );
        }
        else{
            $administrator->setUnblocked(0);
            $em = $this->getDoctrine()->getManager();
            $em->persist($administrator);
            $em->flush();
            $this->addFlash(
                'success',
                'Admin blocked successfully'
            );
        }
        return $this->redirectToRoute('administrators_index', array('page' => $page));
    }

    /**
     * Unblocks selected administrator user
     *
     */
    public function unblockAction(Administrators $administrator, $page){
        $administrator->setUnblocked(1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($administrator);
        $em->flush();
        $this->addFlash(
            'success',
            'Admin unblocked successfully'
        );
        return $this->redirectToRoute('administrators_index', array('page' => $page));
    }

    /**
     * Deletes a Administrators entity.
     *
     */
    public function deleteAction(Request $request, Administrators $administrator)
    {
        $form = $this->createDeleteForm($administrator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            //You can't delete yourself
            if($administrator->getId() === $user->getId()){

            }
            else{
                $em = $this->getDoctrine()->getManager();
                $em->remove($administrator);
                $em->flush();
            }
        }

        return $this->redirectToRoute('administrators_index');
    }

    /**
     * Creates a form to delete a Administrators entity.
     *
     * @param Administrators $administrator The Administrators entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Administrators $administrator)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('administrators_delete', array('id' => $administrator->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
