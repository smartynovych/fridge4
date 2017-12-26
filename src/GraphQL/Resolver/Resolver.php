<?php

namespace App\GraphQL\Resolver;

use App\Entity\Product;
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
        $product = $this->em->getRepository(Product::class)->findByExpiredDate(new \DateTime('-1 day'));

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
