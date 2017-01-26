<?php

namespace PoiBundle\Controller;

use PoiBundle\Form\AdministratorsType;
use PoiBundle\Entity\Administrators;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class RegistrationController extends Controller
{
    /**
     * @Security("is_granted('ADD', request)")
     */
    public function adminAction(Request $request)
    {
        $admin = new Administrators();
        $form = $this->createForm('PoiBundle\Form\AdministratorsType', $admin);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $admin->setUnblocked(1);
            $admin->setFirstlogin(1);
            $password = $this->get('security.password_encoder')
            ->encodePassword($admin, $admin->getPlainPassword());
            $admin->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($admin);
            $em->flush();

            return $this->redirectToRoute('administrators_index');
        }

        return $this->render(
            'registration/admin.html.twig',
            array('form' => $form->createView())
        );
    }

}