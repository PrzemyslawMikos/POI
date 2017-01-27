<?php

namespace PoiBundle\Repository;
use PoiBundle\Entity\Types;

class TypesRepository extends \Doctrine\ORM\EntityRepository
{

    public function findAllQuery()
    {
        $query = $this->getEntityManager()->createQueryBuilder();

        $query
            ->select(['Types'])
            ->from('PoiBundle:Types', 'Types');

        try {
            return $query->getQuery();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function findByIdRestResult($id)
    {
        $query = $this->getEntityManager()->createQueryBuilder();

        $query
            ->select('Type.id, Type.name, Type.description, Type.addeddate, Type.image, Type.mimetype')
            ->from('PoiBundle:Types', 'Type')
            ->where('Type.id = :id')
            ->setParameter('id', $id);

        try {
            $type = new Types();
            $type = $query->getQuery()->getResult();
            return $type;
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

}