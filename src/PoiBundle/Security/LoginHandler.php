<?php
/**
 * Created by PhpStorm.
 * User: Przemek
 * Date: 27.01.2017
 * Time: 21:00
 */

namespace PoiBundle\Security;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginHandler implements AuthenticationSuccessHandlerInterface
{
    protected $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        if($token->getUser()->getFirstlogin()){
            return new RedirectResponse($this->router->generate('administrators_changepassword', ['id' => $token->getUser()->getId()]));
        }
        else{
            return new RedirectResponse($this->router->generate('main_index'));
        }
    }
}