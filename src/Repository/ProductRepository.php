<?php

namespace App\Repository;

use App\Entity\Section;
use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function findByExpiredDate(\DateTime $date)
    {
        $query = $this->_em->getRepository('App:Product')->createQueryBuilder('p')
            ->leftJoin('p.section', 's');

        $query->where($query->expr()->lt('p.expirationDate', ':expirationDate'))
            ->setParameter('expirationDate', $date)
            ->addOrderBy('p.expirationDate', 'ASC');

        return $query->getQuery()->getResult();
    }

    public function getSumVolume($sectionId)
    {
        $query = $this->_em->getRepository('App:Product')->createQueryBuilder('p')
            ->select('SUM(p.volume) as volume')
            ->where('p.section = :section')
            ->setParameter('section', $sectionId);

        return $query->getQuery()->getOneOrNullResult();
    }
}
