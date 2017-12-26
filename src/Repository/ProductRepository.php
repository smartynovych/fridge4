<?php

namespace App\Repository;

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
}
