<?php

namespace PoiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use PoiBundle\Additional\RestConstants;
use PoiBundle\Entity\Application\TypesAndroid;
use PoiBundle\Entity\Application\UsersAndroid;
use PoiBundle\Entity\Users;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        try{
            $parametersAsArray = [];
            if ($content = $request->getContent()) {
                $parametersAsArray = json_decode($content, true);
                if(!isset($parametersAsArray["username"]) || !isset($parametersAsArray["password"])){
                    return new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_WRONG_PARAMS], Response::HTTP_BAD_REQUEST);
                }
            }
            $user = $this->getDoctrine()
                ->getRepository('PoiBundle:Users')
                ->findOneBy(['username' => $parametersAsArray["username"]]);
            if (!$user) {
                return new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_BAD_CREDENTIALS], Response::HTTP_BAD_REQUEST);
            }
            if (!$user->getUnblocked()){
                return new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_USER_BLOCKED], Response::HTTP_NOT_ACCEPTABLE);
            }
            $isValid = $this->get('security.password_encoder')
                ->isPasswordValid($user, $parametersAsArray["password"]);
            if (!$isValid) {
                return new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_BAD_CREDENTIALS], Response::HTTP_BAD_REQUEST);
            }
            $token = $this->get('lexik_jwt_authentication.encoder')
                ->encode([
                    'username' => $user->getUsername(),
                    'exp' => time() + RestConstants::TOKEN_TIME_VALID
                ]);
            return new JsonResponse([RestConstants::JSON_TOKEN => $token, RestConstants::JSON_USER_ID => $user->getId()], Response::HTTP_OK);
        }
        catch (\Exception $e){
            return new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_INTERNAL_SERVER_ERROR], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Zwraca dane użytkownika
     *
     * @Get("/user/{id}")
     */
    public function getUserAction($id)
    {
        try{
            $user = $this->getDoctrine()->getRepository('PoiBundle:Users')->find($id);
            if ($user === null) {
                return new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_NOT_FOUND], Response::HTTP_BAD_REQUEST);
            }
            $userAndroid = UsersAndroid::constructUser($user);
            return $userAndroid;
        }
            catch (\Exception $e){
            return new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_INTERNAL_SERVER_ERROR], Response::HTTP_BAD_REQUEST);
        }
    }

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
            $serverUser = $this->getDoctrine()->getRepository('PoiBundle:Users')->findOneBy(array('username' => $userAndroid->getUsername()));
            if(isset($serverUser)){
                return new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_USERNAME_EXIST], Response::HTTP_BAD_REQUEST);
            }
            $serverUser = $this->getDoctrine()->getRepository('PoiBundle:Users')->findOneBy(array('email' => $userAndroid->getEmail()));
            if(isset($serverUser)){
                return new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_EMAIL_EXIST], Response::HTTP_BAD_REQUEST);
            }
            $user = Users::constructUserAndroid($userAndroid);
            $user->setPermission($this->getDoctrine()->getManager()->getRepository('PoiBundle:Permissions')->find($userAndroid->getPermissionid()));
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $userAndroid->getPassword());
            $user->setPassword($encoded);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_OK], Response::HTTP_OK);
        }
        catch (\Exception $e){
            return new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_INTERNAL_SERVER_ERROR], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

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
        try{
            $pointAndroid = PointsAndroid::constructRequest($request);
            $fileName = $this->get('poi.points_uploader')->upload($pointAndroid->getPicture(), $pointAndroid->getMimetype());
            $pointAndroid->setPicture($fileName);
            $point = Points::constructPointAndroid($pointAndroid);
            $point->setType($this->getDoctrine()->getManager()->getRepository('PoiBundle:Types')->find($pointAndroid->getTypeid()));
            $point->setUser($this->getDoctrine()->getManager()->getRepository('PoiBundle:Users')->find($pointAndroid->getUserid()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($point);
            $em->flush();
            return new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_OK], Response::HTTP_OK);
        }
            catch (\Exception $e){
            return new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_INTERNAL_SERVER_ERROR], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Zwraca listę typów
     *
     * @Get("/types")
     */
    public function getTypesAction()
    {
        try{
            $types = $this->getDoctrine()->getRepository('PoiBundle:Types')->findAll();
            if ($types === null) {
                return new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_NOT_FOUND], Response::HTTP_NOT_FOUND);
            }
            $typesAndroid = array();
            foreach($types as $type){
                $typeAndroid = TypesAndroid::constructType($type);
                array_push($typesAndroid, $typeAndroid);
            }
            return $typesAndroid;
        }
            catch (\Exception $e){
            return new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_INTERNAL_SERVER_ERROR], Response::HTTP_BAD_REQUEST);
        }
    }

    //TODO dodać więcej opcji filtrowania!
    /**
     * Zwraca punkty z odpowiednimi wymaganiami
     * Jeśli locality = * wtedy znajedzie wszystkie punkty danego typu niezależnie od miejscowośći
     *
     * @Get("/points/{typeid}/{locality}/{limit}/{offset}")
     */
    public function getPointsCriteriaAction($typeid, $locality, $limit, $offset)
    {
        try{
            if($locality === '*'){
                $locality = '%';
            }
            $points = $this->getDoctrine()->getRepository('PoiBundle:Points')->findByCriteriaRestResult($typeid, $locality, $limit, $offset);
            if (!isset($points) or empty($points) or is_null($points)) {
                return new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_NOT_FOUND], Response::HTTP_NOT_FOUND);
            }
            else{
                $pointsAndroid = array();
                foreach($points as $point){
                    $pointAndroid = PointsAndroid::constructPoint($point);
                    array_push($pointsAndroid, $pointAndroid);
                }
                return $pointsAndroid;
            }
        }
            catch (\Exception $e){
            return new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_INTERNAL_SERVER_ERROR], Response::HTTP_BAD_REQUEST);
        }
    }
}