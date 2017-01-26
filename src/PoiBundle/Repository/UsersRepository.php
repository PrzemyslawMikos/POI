<?php

namespace PoiBundle\Repository;

class UsersRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllQuery()
    {
        $query = $this->getEntityManager()->createQueryBuilder();

        $query
            ->select(['Users'])
            ->from('PoiBundle:Users', 'Users');

        try {
            return $query->getQuery();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function findByUnblockedQuery($unblocked){
        $query = $this->getEntityManager()->createQueryBuilder();

        $query
            ->select(['Users'])
            ->from('PoiBundle:Users', 'Users')
            ->where('Users.unblocked = :unblocked')
            ->setParameter('unblocked', $unblocked);

        try {
            return $query->getQuery();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function searchAllLikeResult($searchValue){
        $query = $this->getEntityManager()->createQueryBuilder();

        $query
            ->select('Users')
            ->from('PoiBundle:Users', 'Users')
            ->where('Users.nickname LIKE :searchValue')
            ->orWhere('Users.email LIKE :searchValue')
            ->orWhere('Users.username LIKE :searchValue')
            ->setParameter('searchValue', $searchValue);
        try {
            return $query->getQuery()->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

}