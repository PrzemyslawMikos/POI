<?php

namespace PoiBundle\Repository;

class AdministratorsRepository extends \Doctrine\ORM\EntityRepository
{

    public function findAllQuery()
    {
        $query = $this->getEntityManager()->createQueryBuilder();

        $query
            ->select(['Administrators'])
            ->from('PoiBundle:Administrators', 'Administrators');

        try {
            return $query->getQuery();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

}