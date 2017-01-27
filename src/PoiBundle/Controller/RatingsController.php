<?php

namespace PoiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PoiBundle\Entity\Ratings;
use PoiBundle\Form\RatingsType;

class RatingsController extends Controller
{

    public function deleteAction(Request $request, Ratings $rating)
    {
        $form = $this->createDeleteForm($rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($rating);
            $em->flush();
        }

        return $this->redirectToRoute('ratings_index');
    }

    private function createDeleteForm(Ratings $rating)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ratings_delete', array('id' => $rating->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}