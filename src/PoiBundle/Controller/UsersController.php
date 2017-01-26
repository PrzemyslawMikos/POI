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
    /**
     * Lists all Users entities.
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * Creates a new Users entity. Sending user entity in Json format
     *
     */
   /* public function newAction(Request $request)
    {
        $user = new Users();
        $form = $this->createForm('PoiBundle\Form\UsersType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$em = $this->getDoctrine()->getManager();
            //$em->persist($user);
            //$em->flush();
            $encoders = array(new XmlEncoder(), new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());

            $serializer = new Serializer($normalizers, $encoders);
            $jsondata = $serializer->serialize($user, 'json');
            $request->headers->set('Content-Type', 'application/json');
            $request->request->set('user', $jsondata);
            return $this->forward('PoiBundle:Application:register');
        }

        return $this->render('users/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }*/

    /**
     * Creates a new Users entity. Original !
     *
     */
    public function newAction(Request $request)
    {
        $user = new Users();
        $form = $this->createForm('PoiBundle\Form\UsersType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('users_show', array('id' => $user->getId()));
        }

        return $this->render('users/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Users entity.
     *
     */
    public function showAction(Users $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        return $this->render('users/show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Users entity.
     *
     */
    public function editAction(Request $request, Users $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('PoiBundle\Form\UsersType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('users_edit', array('id' => $user->getId()));
        }

        return $this->render('users/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Blocks selected administrator user
     *
     */
    public function blockAction(Users $user, $page){
        $user->setUnblocked(0);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        $this->addFlash(
            'success',
            'Użytkownik zablokowany prawidłowo'
        );

        return $this->redirectToRoute('users_index', array('page' => $page));
    }

    /**
     * Unblocks selected administrator user
     *
     */
    public function unblockAction(Users $user, $page){
        $user->setUnblocked(1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        $this->addFlash(
            'success',
            'Użytkownik odblokowany prawidłowo'
        );
        return $this->redirectToRoute('users_index', array('page' => $page));
    }

    /**
     * Lists unblocked users entities.
     *
     */
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

    /**
     * Lists blocked users entities.
     *
     */
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


    /**
     * Deletes a Users entity.
     *
     */
    public function deleteAction(Request $request, Users $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('users_index');
    }

    /**
     * Creates a form to delete a Users entity.
     *
     * @param Users $user The Users entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Users $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('users_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
