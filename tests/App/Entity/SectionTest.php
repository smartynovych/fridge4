<?php

namespace Tests\App\Entity;

use App\Entity\Section;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SectionTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var section
     */
    private $section;

    /**
     * @setUp
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->section = new Section();
    }

    /**
     * Try to set and get name.
     */
    public function testName()
    {
        $name = 'Main';
        $this->section->setName($name);

        $this->assertEquals($name, $this->section->getName());
    }

    /**
     * Try to set and get description.
     */
    public function testDescription()
    {
        $description = 'Main section';
        $this->section->setDescription($description);

        $this->assertEquals($description, $this->section->getDescription());
    }

    /**
     * Try to set and get volume.
     */
    public function testVolume()
    {
        $volume = 100;
        $this->section->setVolume($volume);

        $this->assertEquals($volume, $this->section->getVolume());
    }

    /**
     * Try to get products.
     */
    public function testProducts()
    {
        $section = $this->em->getRepository('App:Section')->findOneBy(['name' => 'Main']);
        $products = $section->getProducts();
        $this->assertEquals('Bear', $products[0]->getName());
    }
}
