<?php

namespace PoiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use PoiBundle\Entity\Control;
use PoiBundle\Form\ControlType;

/**
 * Control controller.
 *
 */
class ControlController extends Controller
{
    /**
     * Lists all Control entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $controls = $em->getRepository('PoiBundle:Control')->findAll();

        return $this->render('control/index.html.twig', array(
            'controls' => $controls,
        ));
    }

    /**
     * Finds and displays a Control entity.
     *
     */
    public function showAction(Control $control)
    {

        return $this->render('control/show.html.twig', array(
            'control' => $control
        ));
    }

    /**
     * Displays a form to edit an existing Control entity.
     *
     */
    public function editAction(Request $request, Control $control)
    {
        $editForm = $this->createForm('PoiBundle\Form\ControlType', $control);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($control);
            $em->flush();

            return $this->redirectToRoute('control_edit', array('id' => $control->getId()));
        }

        return $this->render('control/edit.html.twig', array(
            'control' => $control,
            'edit_form' => $editForm->createView()
        ));
    }
}
