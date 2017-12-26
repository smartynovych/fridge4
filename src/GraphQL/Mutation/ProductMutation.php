<?php

namespace App\GraphQL\Mutation;

use App\Entity\Product;
use App\Entity\Section;
use Doctrine\ORM\EntityManager;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Error\UserError;

class ProductMutation implements MutationInterface, AliasedInterface
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function create(Argument $args)
    {
        $section = $this->em->getRepository(Section::class)->find($args['input']['section']);
        if (!$section) {
            throw new UserError('Section is not defined');
        }

        $sectionVolume = $this->em->getRepository(Product::class)->getSumVolume($section);

        if (($section->getVolume() - $sectionVolume['volume']) < $args['input']['volume']) {
            throw new UserError(sprintf('Section %s is crowded, the available section space is %.1fdm³', $section->getName(), ($section->getVolume() - $sectionVolume['volume'])));
        }

        $product = new Product();
        $product->setName($args['input']['name']);
        $product->setDescription($args['input']['description']);
        $product->setVolume($args['input']['volume']);
        $product->setExpirationDate($args['input']['expirationDate']);
        $product->setCreatedAt(new \DateTime());
        $product->setSection($section);

        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }

    public function update(Argument $args)
    {
        $product = $this->em->getRepository(Product::class)->find($args['id']);
        if (!$product) {
            throw new UserError('Product is not found');
        }

        if (isset($args['input']['section'])) {
            $section = $this->em->getRepository(Section::class)->find($args['input']['section']);
            if (!$section) {
                throw new UserError('Section is not defined');
            }
            $product->setSection($section);
        }

        $sectionVolume = $this->em->getRepository(Product::class)->getSumVolume($product->getSection());

        if (($product->getSection()->getVolume() - $sectionVolume['volume']) < $args['input']['volume']) {
            throw new UserError(sprintf('Section %s is crowded, the available section space is %.1fdm³', $product->getSection()->getName(), ($product->getSection()->getVolume() - $sectionVolume['volume'])));
        }

        $product->setName($args['input']['name']);
        $product->setDescription($args['input']['description']);
        $product->setVolume($args['input']['volume']);
        $product->setExpirationDate($args['input']['expirationDate']);

        if (isset($args['input']['createdAt'])) {
            $product->setCreatedAt($args['input']['createdAt']);
        }

        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }

    public function delete(int $id)
    {
        $product = $this->em->getRepository(Product::class)->find($id);
        if (!$product) {
            throw new UserError('Product is not found');
        }

        $this->em->remove($product);
        $this->em->flush();

        $status = 'Success';

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
