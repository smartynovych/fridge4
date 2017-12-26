<?php

namespace App\GraphQL\Mutation;

use App\Entity\Product;
use App\Entity\Section;
use Doctrine\ORM\EntityManager;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Overblog\GraphQLBundle\Definition\Argument;

class Mutation implements MutationInterface, AliasedInterface
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function create(Argument $args)
    {
        $product = new Product();
        $product->setName($args['input']['name']);
        $product->setDescription($args['input']['description']);
        $product->setVolume($args['input']['volume']);
        $product->setExpirationDate($args['input']['expirationDate']);
        $product->setCreatedAt(new \DateTime());

        $section = $this->em->getRepository(Section::class)->find($args['input']['section']);
        $product->setSection($section);

        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }

    public function update(Argument $args)
    {
        $product = $this->em->getRepository(Product::class)->find($args['id']);
        if ($product) {
            $product->setName($args['input']['name']);
            $product->setDescription($args['input']['description']);
            $product->setVolume($args['input']['volume']);
            $product->setExpirationDate($args['input']['expirationDate']);

            if (isset($args['input']['createdAt'])) {
                $product->setCreatedAt($args['input']['createdAt']);
            }

            $section = $this->em->getRepository(Section::class)->find($args['input']['section']);
            $product->setSection($section);

            $this->em->persist($product);
            $this->em->flush();
        }

        return $product;
    }

    public function delete(int $id)
    {
        $product = $this->em->getRepository(Product::class)->find($id);
        if ($product) {
            $this->em->remove($product);
            $this->em->flush();
            $status = 'success';
        } else {
            $status = 'product is not found';
        }

        return $status;
    }

    public static function getAliases()
    {
        return [
            'create' => 'Create',
            'update' => 'Update',
            'delete' => 'Delete',
        ];
    }
}
