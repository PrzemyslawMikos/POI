<?php

namespace PoiBundle\Controller;

use Exception;
use PoiBundle\Additional\PaginationHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PoiBundle\Additional\GoogleApiHelper;
use PoiBundle\Additional\ChangesManager;
use PoiBundle\Entity\Points;
use PoiBundle\Entity\Versions;
use PoiBundle\Entity\Changes;
use PoiBundle\Entity\Control;
use PoiBundle\Form\PointsType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use PoiBundle\Controller\ChangesController;
use Symfony\Component\Validator\Constraints\DateTime;

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
            'page' => $page
        ));
    }

    public function enabledAction($page = 1)
    {
        $query = $this->getDoctrine()->getRepository('PoiBundle:Points')->findAcceptedAndUnblockedQuery(true, true);

        $paginationHelper = new PaginationHelper($query, $this->getParameter("points_index_elements"), $page);
        $paginationHelper->makePagination();

        return $this->render('points/enabled.html.twig', array(
            'points' => $paginationHelper,
            'page' => $page
        ));
    }

    public function disabledAction($page = 1)
    {
        $query = $this->getDoctrine()->getRepository('PoiBundle:Points')->findAcceptedAndUnblockedQuery(true, false);

        $paginationHelper = new PaginationHelper($query, $this->getParameter("points_index_elements"), $page);
        $paginationHelper->makePagination();

        return $this->render('points/blocked.html.twig', array(
            'points' => $paginationHelper,
            'page' => $page
        ));
    }

    public function acceptableAction($page = 1)
    {
        $query = $this->getDoctrine()->getRepository('PoiBundle:Points')->findAcceptedAndUnblockedQuery(false, true);

        $paginationHelper = new PaginationHelper($query, $this->getParameter("points_index_elements"), $page);
        $paginationHelper->makePagination();

        return $this->render('points/acceptable.html.twig', array(
            'points' => $paginationHelper,
            'page' => $page
        ));
    }

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

    public function editAction(Request $request, Points $point)
    {
        $deleteForm = $this->createDeleteForm($point);
        $editForm = $this->createForm('PoiBundle\Form\PointsType', $point);
        $oldImage = $point->getPicture();
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            try{
                $file = $point->getPicture();

                if($file == null){
                    $point->setPicture($oldImage);
                }else{
                    $this->get('poi.points_uploader')->delete($oldImage);
                    $mimetype = $this->get('poi.points_uploader')->getMimeType($file);
                    $point->setMimetype($mimetype);
                    $fileName = $this->get('poi.points_uploader')->upload($file);
                    $point->setPicture($fileName);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($point);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Miejsce edytowane prawidłowo');
                return $this->redirectToRoute('points_index', array('page' => 1));
            }
            catch (\Exception $e){
                $this->addFlash(
                    'error',
                    'Błąd podczas edycji miejsca');
            }
        }
        else if($editForm->isSubmitted() && !$editForm->isValid()){
            $this->addFlash(
                'error',
                'Błąd podczas edycji miejsca');
            $this->getDoctrine()->getManager()->refresh($point);
            $editForm = $this->createForm('PoiBundle\Form\PointsType', $point);
            return $this->render('points/edit.html.twig', array(
                'point' => $point,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }

        return $this->render('points/edit.html.twig', array(
            'point' => $point,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function blockAction(Points $point, $page){
        try{
            $point->setUnblocked(0);
            $em = $this->getDoctrine()->getManager();
            $em->persist($point);
            $em->flush($point);
            $this->addFlash(
                'success',
                'Miejsce zablokowane prawidłowo');
        }catch(Exception $e){
            $this->addFlash(
                'error',
                'Nie można zablokować tego miejsca');
        }

        return $this->redirectToRoute('points_index', array('page' => $page));
    }

    /**
     * Promote selected Point entity
     * @param Request $request
     * @param Points $point
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function promoteAction(Request $request, Points $point){
        $address = GoogleApiHelper::getGoogleAddress($point->getLatitude(), $point->getLongitude(), $this->getParameter("points_geo_language"));

        $form =  $this->createFormBuilder()
            ->add('promote', SubmitType::class)
            ->add('block', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($form->get('promote')->isClicked()){
                $em = $this->getDoctrine()->getManager();
                $point->setAccept($em->getRepository('PoiBundle:Administrators')->find($this->getUser()->getId()));
                $point->setAccepted(true);
                $em->persist($point);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Miejsce zostało zatwierdzone poprawnie');
            }
            else{
                $em = $this->getDoctrine()->getManager();
                $point->setAccept($em->getRepository('PoiBundle:Administrators')->find($this->getUser()->getId()));
                $point->setAccepted(true);
                $point->setUnblocked(false);
                $em->persist($point);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Miejsce zostało zablokowane poprawnie');
            }

            return $this->redirectToRoute('points_index', array('page' => 1));
        }

        $nearPoints = $this->getDoctrine()->getRepository('PoiBundle:Points')->findByDistanceResult($point->getLatitude(), $point->getLongitude(), 100);

        return $this->render('points/promote.html.twig', array(
            'point' => $point,
            'address' => $address,
            'form' => $form->createView(),
            'near_points' => $nearPoints
        ));
    }

    public function unblockAction(Points $point, $page){
        try{
            $point->setUnblocked(1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($point);
            $em->flush($point);
            $this->addFlash(
                'success',
                'Miejsce odblokowane prawidłowo');
        }catch(Exception $e){
            $this->addFlash(
                'error',
                'Nie można odblokować tego miejsca');
        }

        return $this->redirectToRoute('points_index', array('page' => $page));
    }

    public function deleteAction(Request $request, Points $point)
    {
        $form = $this->createDeleteForm($point);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try{
                $em = $this->getDoctrine()->getManager();
                $em->remove($point);
                $em->flush();
            }
            catch (\Exception $e){

                $this->addFlash(
                    'error',
                    'Nie można usunąć miejsca o nr. ID: '.$point->getId());
                return $this->redirectToRoute('points_index');
            }
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
                ->getForm();
    }

}