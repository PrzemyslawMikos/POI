<?php

namespace PoiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use PoiBundle\Entity\Application\TypesAndroid;
use PoiBundle\Entity\Application\UsersAndroid;
use PoiBundle\Entity\Users;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use PoiBundle\Entity\Points;
use PoiBundle\Entity\Application\PointsAndroid;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;

class ApiController extends FOSRestController
{
    /**
     * Pozyskanie tokenu przez użytkownika (logowanie, odnowienie tokenu)
     *
     * Przykład żądania:
     * {
	 *   "username":"username",
	 *   "password":"user"
     * }
     *
     * @Post("/token")
     */
    public function postTokenAction(Request $request){

        $parametersAsArray = [];
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
            if(!isset($parametersAsArray["username"]) || !isset($parametersAsArray["password"])){
                return new View("Wrong parameters", Response::HTTP_BAD_REQUEST);
            }
        }
        $user = $this->getDoctrine()
            ->getRepository('PoiBundle:Users')
            ->findOneBy(['username' => $parametersAsArray["username"]]);
        if (!$user) {
            return new View("Bad credentials", Response::HTTP_UNAUTHORIZED);
        }
        $isValid = $this->get('security.password_encoder')
            ->isPasswordValid($user, $parametersAsArray["password"]);
        if (!$isValid) {
            return new View("Bad credentials", Response::HTTP_UNAUTHORIZED);
        }
        $token = $this->get('lexik_jwt_authentication.encoder')
            ->encode([
                'username' => $user->getUsername(),
                'exp' => time() + 3600
            ]);
        return new JsonResponse(['token' => $token, 'userid' => $user->getId()]);
    }

    /**
     * Zwraca dane użytkownika
     *
     * @Get("/user/{id}")
     */
    public function getUserAction($id)
    {
        $user = $this->getDoctrine()->getRepository('PoiBundle:Users')->find($id);
        if ($user === null) {
            return new View("No user with given id", Response::HTTP_NOT_FOUND);
        }
        $userAndroid = UsersAndroid::constructUser($user);

        return $userAndroid;
    }

    //TODO Wyjątki i statusy
    /**
     * Dodanie nowego użytkownika (rejestracja)
     *
     * Przykład żądania:
     * {
     *  "nickname": "nick",
     *  "email": "nick@vp.pl",
     *  "phone": "+48 789 456 132",
     *  "username": "usernick",
     *  "password": "passworduser"
     * }
     *
     * @Post("/register")
     */
    public function postRegisterAction(Request $request){
        try{
            $userAndroid = UsersAndroid::constructRequest($request);
            $user = Users::constructUserAndroid($userAndroid);
            $user->setPermission($this->getDoctrine()->getManager()->getRepository('PoiBundle:Permissions')->find($userAndroid->getPermissionid()));
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $userAndroid->getPassword());
            $user->setPassword($encoded);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return new JsonResponse(['status' => 'true']);
        }
        catch (\Exception $e){
            return new View("Wrong", Response::HTTP_BAD_REQUEST);
        }
    }

    //TODO Wyjątki i statusy
    /**
     * Dodanie nowego punktu
     *
     * Przykład żądania:
     * {
	 *  "longitude":"33.444",
	 *  "latitude":"4.333",
	 *  "name":"name",
	 *  "description":"description",
	 *  "picture":"picture",
	 *  "mimetype":"mimetype",
	 *  "typeid":"1",
	 *  "userid":"1"
     * }
     *
     * @Post("/point")
     */
    public function postPointAction(Request $request)
    {
        $pointAndroid = PointsAndroid::constructRequest($request);
        $point = Points::constructPointAndroid($pointAndroid);
        $point->setType($this->getDoctrine()->getManager()->getRepository('PoiBundle:Types')->find($pointAndroid->getTypeid()));
        $point->setUser($this->getDoctrine()->getManager()->getRepository('PoiBundle:Users')->find($pointAndroid->getUserid()));
        $em = $this->getDoctrine()->getManager();
        $em->persist($point);
        $em->flush();
        return new JsonResponse(['status' => 'true']);
    }

    //TODO Wyjątki i statusy
    /**
     * Zwraca listę typów
     *
     * @Get("/types")
     */
    public function getTypesAction()
    {
        $types = $this->getDoctrine()->getRepository('PoiBundle:Types')->findAll();
        if ($types === null) {
            return new View("No type with given id", Response::HTTP_NOT_FOUND);
        }
        $typesAndroid = array();
        foreach($types as $type){
            $typeAndroid = TypesAndroid::constructType($type);
            array_push($typesAndroid, $typeAndroid);
        }
        return $typesAndroid;
    }

    /**
     * Zwraca punkt o podanym id
     *
     * @Get("/point/{id}")
     */
    public function getPointAction($id)
    {
        $point = $this->getDoctrine()->getRepository('PoiBundle:Points')->find($id);
        if ($point === null) {
            return new View("No point found with given id", Response::HTTP_NOT_FOUND);
        }
        else{
            $pointAndroid = PointsAndroid::constructPoint($point);
        }
        return $pointAndroid;
    }

    //TODO dodać więcej opcji filtrowania!
    /**
     * Zwraca punkty z odpowiednimi wymaganiami
     *
     * @Get("/points/{typeid}/{city}")
     */
    public function getPointsOffsetAction($typeid, $city)
    {
        $point = $this->getDoctrine()->getRepository('PoiBundle:Points')->find($typeid);
        if ($point === null) {
            return new View("No point found with given id", Response::HTTP_NOT_FOUND);
        }
        else{
            $pointAndroid = PointsAndroid::constructPoint($point);
        }
        return $pointAndroid;
    }

//    // Return type by id
//    public function getTypesAction()
//    {
//        $type = $this->getDoctrine()->getRepository('PoiBundle:Types')->find($id);
//        if ($type === null) {
//            return new View("No type with given id", Response::HTTP_NOT_FOUND);
//        }
//        return $type;
//    }


    public function putPointsAction()
    {
        return new Response("Put!");
    }

}