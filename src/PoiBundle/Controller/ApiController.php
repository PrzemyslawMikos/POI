<?php

namespace PoiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use PoiBundle\Additional\RestConstants;
use PoiBundle\Entity\Application\RatingsAndroid;
use PoiBundle\Entity\Application\TypesAndroid;
use PoiBundle\Entity\Application\UsersAndroid;
use PoiBundle\Entity\Application\PointsAndroid;
use PoiBundle\Entity\Ratings;
use PoiBundle\Entity\Users;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PoiBundle\Entity\Points;
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
            return new JsonResponse([RestConstants::JSON_TOKEN_KEY => $token, RestConstants::JSON_USERID_KEY => $user->getId()], Response::HTTP_OK);
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
     *  "rating":"5"
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
            $fileName = $this->get('poi.points64_uploader')->upload($pointAndroid->getPicture(), $pointAndroid->getMimetype());
            $pointAndroid->setPicture($fileName);
            $point = Points::constructPointAndroid($pointAndroid);
            $point->setType($this->getDoctrine()->getManager()->getRepository('PoiBundle:Types')->find($pointAndroid->getTypeid()));
            $point->setUser($this->getDoctrine()->getManager()->getRepository('PoiBundle:Users')->find($pointAndroid->getUserid()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($point);
            $em->flush();

            $newRating = new Ratings();
            $newRating->setUser($point->getUser());
            $newRating->setPoint($point);
            $newRating->setRating($pointAndroid->getRating());
            $newRating->setRatedate(new \DateTime());
            $em->persist($newRating);
            $em->flush();

            $this->recalculateRating($point->getId());

            return new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_OK], Response::HTTP_OK);
        }
        catch (\Exception $e){
            return new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_INTERNAL_SERVER_ERROR], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Zwraca listę kategorii
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

    /**
     * Zwraca kategorię o podanym id
     *
     * @Get("/types/{typeid}")
     */
    public function getTypeByIdAction($typeid){
        try{
            $type = $this->getDoctrine()->getRepository('PoiBundle:Types')->find($typeid);
            if ($type === null) {
                return new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_NOT_FOUND], Response::HTTP_NOT_FOUND);
            }
            $typeAndroid = TypesAndroid::constructType($type);
            return $typeAndroid;
        }
        catch (\Exception $e){
            return new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_INTERNAL_SERVER_ERROR], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Zwraca punkty z odpowiednimi wymaganiami
     * Jeśli locality === '*' znajedzie wszystkie punkty danego typu niezależnie od miejscowośći
     * Jeśli typeid == 0 znajdzie wszystkie punkty o podanej lokalizacji niezależnie od typu
     *
     * @Get("/points/{typeid}/{locality}/{limit}/{offset}")
     */
    public function getPointsCriteriaAction($typeid, $locality, $limit, $offset)
    {
        try{
            if($locality === '*'){
                $locality = '%';
            }
            if($typeid == 0){
                $points = $this->getDoctrine()->getRepository('PoiBundle:Points')->findByCriteriaRestNoTypeResult($locality, $limit, $offset);
            }
            else{
                $points = $this->getDoctrine()->getRepository('PoiBundle:Points')->findByCriteriaRestResult($typeid, $locality, $limit, $offset);
            }
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

    /**
     * Zwraca punkty z zakresu odległości w metrach
     *
     * @Get("/points/{userLatitude}/{userLongitude}/{distance}")
     */
    public function getPointsByDistanceAction($userLatitude, $userLongitude, $distance){
        try {
            $points = $this->getDoctrine()->getRepository('PoiBundle:Points')->findByDistanceResult($userLatitude, $userLongitude, $distance);
            if (!isset($points) or empty($points) or is_null($points)) {
                return new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_NOT_FOUND], Response::HTTP_NOT_FOUND);
            }
            else{
                return $points;
            }
        }
        catch (\Exception $e){
            return new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_INTERNAL_SERVER_ERROR], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Ocena punktu
     *
     * Przykład żądania:
     * {
     *  "rating":"4.5",
     *  "pointid":4,
     *  "userid":1
     * }
     *
     * @Post("/rating")
     */
    public function postRatingPointAction(Request $request){
        try{
            $ratingAndroid = RatingsAndroid::constructRequest($request);
            $rating = $this->getDoctrine()->getRepository('PoiBundle:Ratings')->findOneBy(array('point' => $ratingAndroid->getPointid(), 'user' => $ratingAndroid->getUserid()));
            if(!$rating){
                $newRating = new Ratings();
                $newRating->setUser($this->getDoctrine()->getRepository('PoiBundle:Users')->find($ratingAndroid->getUserid()));
                $newRating->setPoint($this->getDoctrine()->getRepository('PoiBundle:Points')->find($ratingAndroid->getPointid()));
                $newRating->setRating($ratingAndroid->getRating());
                $newRating->setRatedate(new \DateTime());
                $em = $this->getDoctrine()->getManager();
                $em->persist($newRating);
                $em->flush();
                $response = new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_NEW], Response::HTTP_OK);
            }
            else{
                $rating->setRating($ratingAndroid->getRating());
                $rating->setRatedate(new \DateTime());
                $em = $this->getDoctrine()->getManager();
                $em->persist($rating);
                $em->flush();
                $response = new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_UPDATED], Response::HTTP_OK);
            }
            $this->recalculateRating($ratingAndroid->getPointid());
            return $response;
        }
        catch (\Exception $e){
            return new JsonResponse([RestConstants::STATUS => RestConstants::STATUS_INTERNAL_SERVER_ERROR], Response::HTTP_BAD_REQUEST);
        }
    }

    private function recalculateRating($pointid){
        $avargeRating = $this->getDoctrine()->getRepository('PoiBundle:Ratings')->getAvargeRating($pointid);
        $point = $this->getDoctrine()->getRepository('PoiBundle:Points')->find($pointid);
        $point->setRating($avargeRating);
        $em = $this->getDoctrine()->getManager();
        $em->persist($point);
        $em->flush();
    }
}