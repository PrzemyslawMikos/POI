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

    public function newAction(Request $request)
    {
        $type = new Types();
        $form = $this->createForm('PoiBundle\Form\TypesType', $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fileName = "";
            try{
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
                $this->addFlash(
                    'success',
                    'Kategoria została dodana prawidłowo');
                return $this->redirectToRoute('types_show', array('id' => $type->getId()));
            }catch (\Exception $e){
                $this->get('poi.types_uploader')->delete($fileName);
                $this->addFlash(
                    'error',
                    'Błąd podczas dodawania kategorii');
                $form = $this->createForm('PoiBundle\Form\TypesType', $type);
                return $this->render('types/new.html.twig', array(
                    'type' => $type,
                    'form' => $form->createView(),
                ));
            }
        }elseif ($form->isSubmitted() && !$form->isValid()){
            $this->addFlash(
                'error',
                'Wypełnij wszystkie pola prawidłowo');
            $form = $this->createForm('PoiBundle\Form\TypesType', $type);
            return $this->render('types/new.html.twig', array(
                'type' => $type,
                'form' => $form->createView(),
            ));
        }
        return $this->render('types/new.html.twig', array(
            'type' => $type,
            'form' => $form->createView(),
        ));
    }

    public function showAction(Types $type)
    {
        $deleteForm = $this->createDeleteForm($type);
        return $this->render('types/show.html.twig', array(
            'type' => $type,
            'delete_form' => $deleteForm->createView(),
        ));
    }

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
            }else {
                $this->get('poi.types_uploader')->delete($oldImage);
                $mimetype = $this->get('poi.types_uploader')->getMimeType($file);
                $type->setMimetype($mimetype);
                $fileName = $this->get('poi.types_uploader')->upload($file);
                $type->setImage($fileName);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($type);
            $em->flush();
            $this->addFlash(
                'success',
                'Kategoria została edytowana prawidłowo');
            return $this->redirectToRoute('types_index');
        }elseif ($editForm->isSubmitted() && !$editForm->isValid()){
            $this->addFlash(
                'error',
                'Błąd podczas edycji kategorii');
            $this->getDoctrine()->getManager()->refresh($type);
            $editForm = $this->createForm('PoiBundle\Form\TypesType', $type);
            return $this->render('points/edit.html.twig', array(
                'type' => $type,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView()
            ));
        }
        return $this->render('types/edit.html.twig', array(
            'type' => $type,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        ));
    }

    public function deleteAction(Request $request, Types $type)
    {
        $form = $this->createDeleteForm($type);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try{
                $image = $type->getImage();
                $em = $this->getDoctrine()->getManager();
                $em->remove($type);
                $em->flush();
                $this->get('poi.types_uploader')->delete($image);
                $this->addFlash(
                    'success',
                    'Kategoria została usunięta'.$type->getId());
            }catch (\Exception $e){
                $this->addFlash(
                    'error',
                    'Nie można usunąć kategorii o nr. ID: '.$type->getId());
                return $this->redirectToRoute('types_index');
            }
        }
        return $this->redirectToRoute('types_index');
    }

    private function createDeleteForm(Types $type)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('types_delete', array('id' => $type->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
