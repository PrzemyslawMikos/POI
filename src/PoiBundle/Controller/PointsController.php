<?php

namespace PoiBundle\Controller;

use Exception;
use PoiBundle\Additional\PaginationHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PoiBundle\Additional\GoogleApiHelper;
use PoiBundle\Entity\Points;
use PoiBundle\Form\PointsType;

/**
 * Points controller.
 *
 */
class PointsController extends Controller
{

    /**
     * Lists all Points entities.
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page = 1)
    {
        $query = $this->getDoctrine()->getRepository('PoiBundle:Points')->findAllQuery();

        $paginationHelper = new PaginationHelper($query, $this->getParameter("points_index_elements"), $page);
        $paginationHelper->makePagination();

        return $this->render('points/index.html.twig', array(
            'points' => $paginationHelper,
            'page' => $page,
        ));
    }

    /**
     * Lists enabled Points entities.
     *
     */
    public function enabledAction()
    {
        $em = $this->getDoctrine()->getManager();

        $points = $em->getRepository('PoiBundle:Points')->findAll();

        return $this->render('points/index.html.twig', array(
            'points' => $points,
        ));
    }

    /**
     * Lists disabled Points entities.
     *
     */
    public function disabledAction()
    {
        $em = $this->getDoctrine()->getManager();

        $points = $em->getRepository('PoiBundle:Points')->findAll();

        return $this->render('points/index.html.twig', array(
            'points' => $points,
        ));
    }

    /**
     * Creates a new Points entity.
     *
     */
    public function newAction(Request $request)
    {
        $point = new Points();
        $form = $this->createForm('PoiBundle\Form\PointsType', $point);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($point);
            $em->flush();

            return $this->redirectToRoute('points_show', array('id' => $point->getId()));
        }

        return $this->render('points/new.html.twig', array(
            'point' => $point,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Points entity.
     *
     */
    public function showAction(Points $point)
    {
        $deleteForm = $this->createDeleteForm($point);
        $address = GoogleApiHelper::getGoogleAddress($point->getLatitude(), $point->getLongitude(), $this->getParameter("points_geo_language"));
        return $this->render('points/show.html.twig', array(
            'point' => $point,
            'address' => $address,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Points entity.
     *
     */
    public function editAction(Request $request, Points $point)
    {
        $deleteForm = $this->createDeleteForm($point);
        $editForm = $this->createForm('PoiBundle\Form\PointsType', $point);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($point);
            $em->flush();

            return $this->redirectToRoute('points_edit', array('id' => $point->getId()));
        }

        return $this->render('points/edit.html.twig', array(
            'point' => $point,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Blocks selected Point entity
     *
     */
    public function blockAction(Points $point, $page){
        try{
            $point->setUnblocked(0);
            $em = $this->getDoctrine()->getManager();
            $em->persist($point);
            $em->flush($point);
            $this->addFlash(
                'success',
                'Point blocked successfully');
        }catch(Exception $e){
            $this->addFlash(
                'error',
                'Can\'t block this point');
        }

        return $this->redirectToRoute('points_index', array('page' => $page));
    }

    /**
     * Unblocks selected Point entity
     *
     */
    public function unblockAction(Points $point, $page){
        try{
            $point->setUnblocked(1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($point);
            $em->flush($point);
            $this->addFlash(
                'success',
                'Point unblocked successfully');
        }catch(Exception $e){
            $this->addFlash(
                'error',
                'Can\'t unblock this point');
        }

        return $this->redirectToRoute('points_index', array('page' => $page));
    }

    /**
     * Deletes a Points entity.
     *
     */
    public function deleteAction(Request $request, Points $point)
    {
        $form = $this->createDeleteForm($point);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($point);
            $em->flush();
        }

        return $this->redirectToRoute('points_index');
    }

    /**
     * Creates a form to delete a Points entity.
     *
     * @param Points $point The Points entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Points $point)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('points_delete', array('id' => $point->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
