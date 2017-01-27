<?php

namespace PoiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PoiBundle\Additional\PaginationHelper;
use PoiBundle\Entity\Users;
use PoiBundle\Form\UsersType;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class UsersController extends Controller
{

    public function indexAction($page = 1)
    {
        $query = $this->getDoctrine()->getRepository('PoiBundle:Users')->findAllQuery();
        $paginationHelper = new PaginationHelper($query, $this->getParameter("users_index_elements"), $page);
        $paginationHelper->makePagination();
        return $this->render('users/index.html.twig', array(
            'users' => $paginationHelper,
            'page' => $page,
        ));
    }

    public function unblockedAction($page = 1)
    {
        $query = $this->getDoctrine()->getRepository('PoiBundle:Users')->findByUnblockedQuery(1);
        $paginationHelper = new PaginationHelper($query, $this->getParameter("users_index_elements"), $page);
        $paginationHelper->makePagination();
        return $this->render('users/unblocked.html.twig', array(
            'users' => $paginationHelper,
            'page' => $page
        ));
    }

    public function blockedAction($page = 1)
    {
        $query = $this->getDoctrine()->getRepository('PoiBundle:Users')->findByUnblockedQuery(0);
        $paginationHelper = new PaginationHelper($query, $this->getParameter("users_index_elements"), $page);
        $paginationHelper->makePagination();
        return $this->render('users/blocked.html.twig', array(
            'users' => $paginationHelper,
            'page' => $page
        ));
    }

    public function showAction(Users $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        return $this->render('users/show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function editAction(Request $request, Users $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('PoiBundle\Form\UsersType', $user);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            try{
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Użytkownik edytowany prawidłowo'
                );
                return $this->redirectToRoute('users_index', array('page' => 1));
            }catch (\Exception $e){
                $this->addFlash(
                    'error',
                    'Błąd podczas edycji użytkownika'
                );
            }
        }elseif ($editForm->isSubmitted() && !$editForm->isValid()){
            $this->addFlash(
                'error',
                'Błąd podczas edycji użytkownika');
            $this->getDoctrine()->getManager()->refresh($user);
            $editForm = $this->createForm('PoiBundle\Form\UsersType', $user);
            return $this->render('users/edit.html.twig', array(
                'user' => $user,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }
        return $this->render('users/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function blockAction(Users $user, $page){
        try{
            $user->setUnblocked(0);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash(
                'success',
                'Użytkownik zablokowany prawidłowo'
            );
        }catch(\Exception $e){
            $this->addFlash(
                'error',
                'Nie można zablokować tego użytkownika');
        }
        return $this->redirectToRoute('users_index', array('page' => $page));
    }

    public function unblockAction(Users $user, $page)
    {
        try{
            $user->setUnblocked(1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash(
                'success',
                'Użytkownik odblokowany prawidłowo'
            );
        }catch(\Exception $e){
            $this->addFlash(
            'error',
            'Nie można odblokować tego użytkownika');
        }
        return $this->redirectToRoute('users_index', array('page' => $page));
    }

    public function deleteAction(Request $request, Users $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try{
                $em = $this->getDoctrine()->getManager();
                $em->remove($user);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Użytkonik został usunięty prawidłowo');
            }catch (\Exception $e){
                $this->addFlash(
                    'error',
                    'Nie można usunąć tego użytkownika');
            }
        }
        return $this->redirectToRoute('users_index');
    }

    private function createDeleteForm(Users $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('users_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}