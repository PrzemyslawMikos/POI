<?php

namespace PoiBundle\Repository;

class RatingsRepository extends \Doctrine\ORM\EntityRepository
{

    public function getAvargeRating($pointid){
        $query = $this->getEntityManager()->createQueryBuilder();

        $query
            ->select('avg(Rating.rating)')
            ->from('PoiBundle:Ratings', 'Rating')
            ->innerJoin('Rating.point', 'Point')
            ->where('Point.id = :pointid')
            ->setParameter('pointid', $pointid);

        try {
            return $query->getQuery()->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

}
