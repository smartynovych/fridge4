<?php

namespace App\DataFixtures;

use App\Entity\Section;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $section = new Section();
        $section->setName('Main');
        $section->setVolume(150);
        $manager->persist($section);

        $product = new Product();
        $product->setName('Bear');
        $product->setDescription('1 bottle');
        $product->setVolume(0.5);
        $product->setExpirationDate(new \DateTime('+ 14 day'));
        $product->setCreatedAt(new \DateTime());
        $product->setSection($section);
        $manager->persist($product);

        $section = new Section();
        $section->setName('Door');
        $section->setVolume(10);
        $manager->persist($section);

        $product = new Product();
        $product->setName('Eggs');
        $product->setDescription('10 eggs');
        $product->setVolume(0.5);
        $product->setExpirationDate(new \DateTime('+ 30 day'));
        $product->setCreatedAt(new \DateTime());
        $product->setSection($section);
        $manager->persist($product);

        $section = new Section();
        $section->setName('Freezer');
        $section->setVolume(50);
        $manager->persist($section);

        $product = new Product();
        $product->setName('Fish');
        $product->setDescription('2 kg');
        $product->setVolume(1.5);
        $product->setExpirationDate(new \DateTime('+ 90 day'));
        $product->setCreatedAt(new \DateTime());
        $product->setSection($section);
        $manager->persist($product);

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
