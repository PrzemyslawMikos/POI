<?php

namespace PoiBundle\Voters;

use PoiBundle\Entity\Administrators;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AdministratorsVoter extends Voter
{
    const ADD = 'ADD';
    const VIEW = 'VIEW';
    const EDIT = 'EDIT';
    const DELETE = 'DELETE';

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, array(
            self::VIEW,
            self::EDIT,
            self::ADD,
            self::DELETE
        ));
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $userRoles = $token->getRoles();
        $userRole = $userRoles[0];
        $user = $token->getUser();
        if($userRole->getRole() === 'ROLE_MASTER_ADMIN'){
            return true;
        }
        else{
            if(is_null($subject)){
                if($attribute === self::VIEW && $userRole->getRole() === 'ROLE_ADMIN'){
                    return false;
                }
            }
            else {
                if ($attribute === self::ADD && $userRole->getRole() === 'ROLE_ADMIN') {
                    return false;
                }

                if ($attribute === self::DELETE && $userRole->getRole() === 'ROLE_ADMIN') {
                    return false;
                }

                if ($attribute === self::EDIT && $userRole->getRole() === 'ROLE_ADMIN' && $subject->getId() === $user->getId()) {
                    return true;
                }

                if ($attribute === self::VIEW && $userRole->getRole() === 'ROLE_ADMIN' && $subject->getId() === $user->getId()) {
                    return true;
                }
            }
            return false;
        }
    }
}