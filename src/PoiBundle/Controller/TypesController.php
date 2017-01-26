<?php

namespace PoiBundle\Controller;

use PoiBundle\Entity\Administrators;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PoiBundle\Entity\Types;
use PoiBundle\Form\TypesType;
use PoiBundle\Additional\PaginationHelper;

class TypesController extends Controller
{
    /**
     * Lists all Types entities.
     *
     */
    public function indexAction($page = 1)
    {
        $query = $this->getDoctrine()->getRepository('PoiBundle:Types')->findAllQuery();

        $paginationHelper = new PaginationHelper($query, $this->getParameter("type_index_elements"), $page);
        $paginationHelper->makePagination();
        return $this->render('types/index.html.twig', array(
            'types' => $paginationHelper,
            'page' => $page,
        ));
    }

    /**
     * Creates a new Types entity.
     *
     */
    public function newAction(Request $request)
    {
        $type = new Types();
        $form = $this->createForm('PoiBundle\Form\TypesType', $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $type->setAddeddate(new \DateTime());
            $file = $type->getImage();
            $mimetype = $this->get('poi.types_uploader')->getMimeType($file);
            $type->setMimetype($mimetype);
            $fileName = $this->get('poi.types_uploader')->upload($file);
            $type->setImage($fileName);

            $em = $this->getDoctrine()->getManager();
            
            $type->setCreator($em->getRepository('PoiBundle:Administrators')->find($this->getUser()->getId()));

            $em->persist($type);
            $em->flush();

            return $this->redirectToRoute('types_show', array('id' => $type->getId()));
        }

        return $this->render('types/new.html.twig', array(
            'type' => $type,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Types entity.
     *
     */
    public function showAction(Types $type)
    {
        $deleteForm = $this->createDeleteForm($type);

        return $this->render('types/show.html.twig', array(
            'type' => $type,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Types entity.
     *
     */
    public function editAction(Request $request, Types $type)
    {
        $deleteForm = $this->createDeleteForm($type);
        $editForm = $this->createForm('PoiBundle\Form\TypesType', $type);
        $oldImage = $type->getImage();
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $file = $type->getImage();

            if($file == null){
                $type->setImage($oldImage);
            }else{
                $this->get('poi.types_uploader')->delete($oldImage);
                $mimetype = $this->get('poi.types_uploader')->getMimeType($file);
                $type->setMimetype($mimetype);
                $fileName = $this->get('poi.types_uploader')->upload($file);
                $type->setImage($fileName);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($type);
            $em->flush();

            return $this->redirectToRoute('types_index');
        }

        return $this->render('types/edit.html.twig', array(
            'type' => $type,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Types entity.
     *
     */
    public function deleteAction(Request $request, Types $type)
    {
        $form = $this->createDeleteForm($type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->get('poi.types_uploader')->delete($type->getImage());
            $em->remove($type);
            $em->flush();
        }

        return $this->redirectToRoute('types_index');
    }

    /**
     * Creates a form to delete a Types entity.
     *
     * @param Types $type The Types entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Types $type)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('types_delete', array('id' => $type->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
