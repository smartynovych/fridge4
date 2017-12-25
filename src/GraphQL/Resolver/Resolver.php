<?php

namespace App\GraphQL\Resolver;

use Doctrine\ORM\EntityManager;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class Resolver implements ResolverInterface, AliasedInterface
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function view(Argument $args)
    {
        $product = $this->em->getRepository('App:Product')->find($args['id']);

        return $product;
    }

    public function viewAll()
    {
        $product = $this->em->getRepository('App:Product')->findAll();

        return ['view' => $product];
    }

    public function viewBy(Argument $args)
    {
        $product = $this->em->getRepository('App:Product')->findBy(
            $args['criteria'],
            $args['orderBy'],
            $args['limit'],
            $args['offset']
        );

        return ['view' => $product];
    }

    public function viewAllExpired()
    {
        $query = $this->em->getRepository('App:Product')->createQueryBuilder('p');

        $query->leftJoin('p.section', 's');
        $query->where($query->expr()->lt('p.expirationDate', ':expirationDate'));
        $query->setParameter('expirationDate', new \DateTime());
        $query->addOrderBy('p.expirationDate', 'ASC');

        $product = $query->getQuery()->getResult();

        return ['view' => $product];
    }

    public static function getAliases()
    {
        return [
            'view' => 'View',
            'viewAll' => 'ViewAll',
            'viewBy' => 'ViewBy',
            'viewAllExpired' => 'ViewAllExpired',
        ];
    }
}
