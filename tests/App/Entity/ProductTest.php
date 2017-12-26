<?php

namespace Tests\App\Entity;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var Product
     */
    private $product;

    /**
     * @setUp
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->product = new Product();
    }

    /**
     * Try to set and get name.
     */
    public function testName()
    {
        $name = 'Cola';
        $this->product->setName($name);

        $this->assertEquals($name, $this->product->getName());
    }

    /**
     * Try to set and get description.
     */
    public function testDescription()
    {
        $description = 'Cold drink';
        $this->product->setDescription($description);

        $this->assertEquals($description, $this->product->getDescription());
    }

    /**
     * Try to set and get volume.
     */
    public function testVolume()
    {
        $volume = 1.5;
        $this->product->setVolume($volume);

        $this->assertEquals($volume, $this->product->getVolume());
    }

    /**
     * Try to set and get expirationDate.
     */
    public function testExpirationDate()
    {
        $expirationDate = new \DateTime();
        $this->product->setExpirationDate($expirationDate);

        $this->assertEquals($expirationDate->format('d.m.Y'), $this->product->getExpirationDate()->format('d.m.Y'));
    }

    /**
     * Try to set and get createdAt.
     */
    public function testCreatedAt()
    {
        $createdAt = new \DateTime();
        $this->product->setCreatedAt($createdAt);

        $this->assertEquals($createdAt->format('d.m.Y H:i:s'), $this->product->getCreatedAt()->format('d.m.Y H:i:s'));
    }

    /**
     * Try to set and get section.
     */
    public function testSection()
    {
        $section = $this->em->getRepository('App:Section')->findOneBy(['name' => 'Main']);
        $this->product->setSection($section);
        $this->assertSame($section, $this->product->getSection());
    }
}
