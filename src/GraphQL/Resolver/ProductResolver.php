<?php

namespace App\GraphQL\Resolver;

use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

/**
 * Class ProductResolver
 * @package App\GraphQL\Resolver
 */
class ProductResolver implements ResolverInterface, AliasedInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * ProductResolver constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param Argument $args
     * @return null|object
     */
    public function view(Argument $args)
    {
        $product = $this->em->getRepository('App:Product')->find($args['id']);

        return $product;
    }

    /**
     * @return array
     */
    public function viewAll()
    {
        $product = $this->em->getRepository('App:Product')->findAll();

        return ['view' => $product];
    }

    /**
     * @param Argument $args
     * @return array
     */
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

    /**
     * @return array
     */
    public function viewAllExpired()
    {
        $product = $this->em->getRepository(Product::class)->findByExpiredDate(new \DateTime('-1 day'));

        return ['view' => $product];
    }

    /**
     * @return array
     */
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
