<?php

namespace PoiBundle\Controller;

use PoiBundle\Additional\JSONHelper;
use PoiBundle\Entity\Users;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ApplicationController extends Controller
{

    public function loginAction(Request $request){

        $JHelper = new JSONHelper();
        $data = $request->request->get('user');
        if( !empty($data)){
            $appuser = $JHelper->deserialize($request->request->get("user"), 'PoiBundle\Entity\Administrators');
            $appuser->setFirstname("1");
            $appuser->setLastname("2");
            $appuser->setPhone("3");
            $appuser->setEmail("5");
            $appuser->setFirstlogin(true);
            $appuser->setUnblocked(true);
            $role = $this->getDoctrine()->getRepository('PoiBundle:Roles')->find(2);
            $appuser->setRole($role);
            $em = $this->getDoctrine()->getManager();
            $em->persist($appuser);
            $em->flush();
            return new Response("Dodano!");
        }
        else{
            return new Response("Dzikie bledy");
        }
    }

    public function indexAction(){
        return new Response('banggg');
        //return $this->render('application/index.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function registerAction(Request $request)
    {
        //$encoders = array(new XmlEncoder(), new JsonEncoder());
        //$normalizers = array(new ObjectNormalizer());
        //$serializer = new Serializer($normalizers, $encoders);
        $data = $request->request->get('user');
        if( !empty($data)) {
            $JHelper = new JSONHelper();
            //$user = $serializer->deserialize($request->request->get("user"), "PoiBundle\Entity\Users", 'json');
            $user = $JHelper->deserialize($data, "PoiBundle\Entity\Users");
            $user->setCreationdate(new \DateTime());
            $permission = $this->getDoctrine()->getRepository("PoiBundle:Permissions")->find(1);
            $user->setPermission($permission);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return new Response("true");
        }
        return new Response("false");
    }
}
