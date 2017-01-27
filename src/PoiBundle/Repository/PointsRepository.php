<?php

namespace PoiBundle\Repository;

class PointsRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllQuery()
    {
        $query = $this->getEntityManager()->createQueryBuilder();

        $query
            ->select(['Points'])
            ->from('PoiBundle:Points', 'Points');

        try {
            return $query->getQuery();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function findAcceptedAndUnblockedQuery($accepted, $unblocked)
    {
        $query = $this->getEntityManager()->createQueryBuilder();

        $query
            ->select(['Points'])
            ->from('PoiBundle:Points', 'Points')
            ->where('Points.accepted = ' . ($accepted ? 'true' : 'false'))
            ->andWhere('Points.unblocked = ' . ($unblocked ? 'true' : 'false'));

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
            ->select('Point')
            ->from('PoiBundle:Points', 'Point')
            ->where('Point.id = :id')
            ->setParameter('id', $id);

        try {
            return $query->getQuery()->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function findByCriteriaRestResult($typeid, $locality, $limit, $offset){
        $query = $this->getEntityManager()->createQueryBuilder();

        $query
            ->select('Points')
            ->from('PoiBundle:Points', 'Points')
            ->innerJoin('Points.type', 'Type')
            ->where('Type.id = :typeid')
            ->andWhere('Points.locality LIKE :locality')
            ->andWhere('Points.accepted = true')
            ->andWhere('Points.unblocked = true')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->setParameter('typeid', $typeid)
            ->setParameter('locality', $locality);

        try {
            return $query->getQuery()->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function findByCriteriaRestNoTypeResult($locality, $limit, $offset){
        $query = $this->getEntityManager()->createQueryBuilder();

        $query
            ->select('Points')
            ->from('PoiBundle:Points', 'Points')
            ->where('Points.locality LIKE :locality')
            ->andWhere('Points.accepted = true')
            ->andWhere('Points.unblocked = true')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->setParameter('locality', $locality);
        try {
            return $query->getQuery()->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function findByDistanceResult($userLatitude, $userLongitude, $distance){
        $manager = $this->getEntityManager();
        $connection = $manager->getConnection();
        $query = $connection->prepare("SELECT poi.points.id, poi.points.longitude,
                      poi.points.latitude, poi.points.rating,
                      poi.points.name, poi.points.locality, 
                      poi.points.description, poi.points.picture,
                      poi.points.mimetype, DATE_FORMAT(poi.points.AddedDate, '%Y-%m-%d') AS addeddate,
                      poi.points.Type_Id AS typeid, poi.points.User_Id AS userid,
                      ROUND((6378.137*1000 * ACos( Cos( RADIANS(poi.points.Latitude) ) * 
                      Cos( RADIANS( :userLatitude ) ) * 
                      Cos( RADIANS( :userLongitude ) - 
                      RADIANS(poi.points.Longitude) ) + 
                      Sin( RADIANS(poi.points.Latitude) ) * 
                      Sin( RADIANS( :userLatitude ) ) )), 0) AS distance 
                      FROM poi.points WHERE poi.points.Accepted = true 
                      AND poi.points.Unblocked = true 
                      HAVING distance <= :distance;");
        $query->bindValue("userLatitude", $userLatitude);
        $query->bindValue("userLongitude", $userLongitude);
        $query->bindValue("distance", $distance);
        $query->execute();
        $results = $query->fetchAll();
        return $results;
    }

    public function searchAllLikeResult($searchValue){
        $query = $this->getEntityManager()->createQueryBuilder();

        $query
            ->select('Points')
            ->from('PoiBundle:Points', 'Points')
            ->innerJoin('Points.type', 'Type')
            ->where('Points.name LIKE :searchValue')
            ->orWhere('Points.id LIKE :searchValue')
            ->orWhere('Points.locality LIKE :searchValue')
            ->orWhere('Points.description LIKE :searchValue')
            ->orWhere('Type.name LIKE :searchValue')
            ->orWhere('Type.description LIKE :searchValue')
            ->setParameter('searchValue', $searchValue);
        try {
            return $query->getQuery()->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}