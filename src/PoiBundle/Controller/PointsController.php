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
            'page' => $page
        ));
    }

    /**
     * Lists enabled Points entities.
     *
     */
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

    /**
     * Lists disabled Points entities.
     *
     */
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

    /**
     * Lists acceptable Points entities.
     *
     */
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
            // Modufy = 1
            $this->AddChanges($point,  $this->getParameter('modify_action_version'));
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
            // Delete = 2
            $this->AddChanges($point,  $this->getParameter('delete_action_version'));
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
     * Promote selected Point entity
     * @param Request $request
     * @param Points $point
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function promoteAction(Request $request, Points $point){
        $address = GoogleApiHelper::getGoogleAddress($point->getLatitude(), $point->getLongitude(), $this->getParameter("points_geo_language"));
        // TODO Create new custom form file, change form name
        $form = $this->createFormBuilder($point)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $point->setAccept($em->getRepository('PoiBundle:Administrators')->find($this->getUser()->getId()));
            $point->setAccepted(true);
            $em->persist($point);
            $em->flush();
            // Add = 0
            $this->AddChanges($point,  $this->getParameter('add_action_version'));

            return $this->redirectToRoute('points_edit', array('id' => $point->getId()));
        }

        return $this->render('points/promote.html.twig', array(
            'point' => $point,
            'address' => $address,
            'form' => $form->createView()
        ));
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
            // Add = 0
            $this->AddChanges($point,  $this->getParameter('add_action_version'));
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
            // Delete = 2
            $this->AddChanges($point,  $this->getParameter('delete_action_version'));
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

    public function GetCurrentVersionAddIfFull(){
        $em = $this->getDoctrine()->getManager();
        $latest = $em->getRepository('PoiBundle:Versions')->findLatestResult();
        if(empty($latest)){
            $version = new Versions();
            $version->setAddeddate(new \DateTime());
            $version->setMembers(0);
            $em = $this->getDoctrine()->getManager();
            $em->persist($version);
            $em->flush();
            return $em->getRepository('PoiBundle:Versions')->findLatestResult();
        }
        else{
            $control = $em->getRepository('PoiBundle:Control')->find(1);
            if($latest->getMembers() < $control->getTonextversion()){
                return $latest;
            }
            else{
                $version = new Versions();
                $version->setAddeddate(new \DateTime());
                $version->setMembers(0);
                $em = $this->getDoctrine()->getManager();
                $em->persist($version);
                $em->flush();
                return $em->getRepository('PoiBundle:Versions')->findLatestResult();
            }
        }
    }

    public function AddChanges($point, $actionType){
        $change = new Changes();
        $currentVersion = $this->GetCurrentVersionAddIfFull();
        $members = $currentVersion->getMembers();
        $members++;
        $currentVersion->setMembers($members);
        $change->setPoint($point);
        $change->setVersion($currentVersion);
        $change->setDate(new \DateTime());
        $change->setActiontype($actionType);
        $em = $this->getDoctrine()->getManager();
        $em->persist($change);
        $em->persist($currentVersion);
        $em->flush();
    }
}
