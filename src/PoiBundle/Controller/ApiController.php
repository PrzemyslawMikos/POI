<?php

namespace PoiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use PoiBundle\Entity\Points;
use PoiBundle\Entity\Application\PointsAndroid;
use PoiBundle\Entity\Types;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class ApiController extends FOSRestController
{
    /*
    * "http://localhost/POI/Web/app_dev.php/api/types/22"
    */

    public function getTokenAction(Request $request){
        $user = $this->getDoctrine()
            ->getRepository('PoiBundle:Users')
            ->findOneBy(['username' => $request->get("username")]);
        if (!$user) {
            return new View("Bad credentials", Response::HTTP_UNAUTHORIZED);
        }

        $isValid = $this->get('security.password_encoder')
            ->isPasswordValid($user, $request->get("password"));

        if (!$isValid) {
            return new View("Bad credentials", Response::HTTP_UNAUTHORIZED);
        }
        $token = $this->get('lexik_jwt_authentication.encoder')
            ->encode([
                'username' => $user->getUsername(),
                'exp' => time() + 3600 // 1 hour expiration
            ]);
        return new JsonResponse(['token' => $token]);
    }



    // Return type by id
    public function getTypesAction($id)
    {
        $type = $this->getDoctrine()->getRepository('PoiBundle:Types')->find($id);
        if ($type === null) {
            return new View("No type with given id", Response::HTTP_NOT_FOUND);
        }
        return $type;
    }

    // Return point by id
    public function getPointsAction($id)
    {
        $point = $this->getDoctrine()->getRepository('PoiBundle:Points')->find($id);
        if ($point === null) {
            return new View("No point with given id", Response::HTTP_NOT_FOUND);
        }
        else{
            $pointAndroid = PointsAndroid::constructPoint($point);
        }
        return $pointAndroid;
    }

    public function postPointsAction(Request $request)
    {
        $pointAndroid = PointsAndroid::constructRequest($request);
        $point = Points::constructPointAndroid($pointAndroid);
        $point->setType($this->getDoctrine()->getManager()->getRepository('PoiBundle:Types')->find($pointAndroid->getTypeid()));
        $point->setUser($this->getDoctrine()->getManager()->getRepository('PoiBundle:Users')->find($pointAndroid->getUserid()));
        $em = $this->getDoctrine()->getManager();
        $em->persist($point);
        $em->flush();
        return true;
    }

    public function putPointsAction()
    {
        return new Response("Put!");
    }

}