<?php

namespace PoiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Main controller.
 *
 */
class MainController extends Controller
{

    public function ajaxAction(Request $request){
        if(!$request->isXmlHttpRequest()){
            //New points (accepted = false, unblocked = true)
            $new_amount = count($this->getDoctrine()->getRepository('PoiBundle:Points')->findBy(array('accepted' =>false)));
            return new Response($new_amount);
        } else {
            return $this->redirect($this->generateUrl('main_index'));
        }
    }

    public function indexAction(){
        //$this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        //Active points (accepted = true, unblocked = true)
        $active_points = $this->getDoctrine()->getRepository('PoiBundle:Points')->findBy(array('unblocked' => true, 'accepted' =>true), array(), $this->getParameter('main_index_tables'), array());
        $active_amount = count($this->getDoctrine()->getRepository('PoiBundle:Points')->findBy(array('unblocked' => true, 'accepted' =>true)));

        //New points (accepted = false, unblocked = true)
        $new_points = $this->getDoctrine()->getRepository('PoiBundle:Points')->findBy(array('accepted' =>false), array(), $this->getParameter('main_index_tables'), array());
        $new_amount = count($this->getDoctrine()->getRepository('PoiBundle:Points')->findBy(array('accepted' =>false)));

        //Blocked points (accepted = true, unblocked = false)
        $blocked_points = $this->getDoctrine()->getRepository('PoiBundle:Points')->findBy(array('unblocked' => false, 'accepted' =>true), array(), $this->getParameter('main_index_tables'), array());
        $blocked_amount = count($this->getDoctrine()->getRepository('PoiBundle:Points')->findBy(array('unblocked' => false, 'accepted' =>true)));

        //Users
        $users = $this->getDoctrine()->getRepository('PoiBundle:Users')->findBy(array(), array(), $this->getParameter('main_index_tables'), array());
        $users_amount = count($this->getDoctrine()->getRepository('PoiBundle:Users')->findAll());

        //Admin added
        $user = $this->getUser();
        $admin_points = $this->getDoctrine()->getRepository('PoiBundle:Points')->findBy(array('accept' => $user->getId()), array(), $this->getParameter('main_index_tables'), array());
        $admin_amount = count($this->getDoctrine()->getRepository('PoiBundle:Points')->findBy(array('accept' => $user->getId())));

        return $this->render('main/index.html.twig',
            array(
                'active_points' => $active_points,
                'active_amount' => $active_amount,
                'new_points' => $new_points,
                'new_amount' => $new_amount,
                'blocked_points' => $blocked_points,
                'blocked_amount' => $blocked_amount,
                'users' => $users,
                'users_amount' => $users_amount,
                'admin_points' => $admin_points,
                'admin_amount' => $admin_amount
            )
        );
    }
}
