<?php

namespace PoiBundle\Controller;

use PoiBundle\Form\AdministratorsType;
use PoiBundle\Entity\Administrators;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends Controller
{

    public function adminAction(Request $request)
    {
        // 1) build the form
        $admin = new Administrators();
        $form = $this->createForm('PoiBundle\Form\AdministratorsType', $admin);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $admin->setUnblocked(1);
            $admin->setFirstlogin(1);
            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
            ->encodePassword($admin, $admin->getPlainPassword());
            $admin->setPassword($password);

            // 4) save the Admin!
            $em = $this->getDoctrine()->getManager();
            $em->persist($admin);
            $em->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the admin

            return $this->redirectToRoute('administrators_index');
        }

        return $this->render(
            'registration/admin.html.twig',
            array('form' => $form->createView())
        );
    }
}