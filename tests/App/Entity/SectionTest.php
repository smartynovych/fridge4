<?php

namespace Tests\App\Entity;

use App\Entity\Section;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class SectionTest extends TestCase
{
    /**
     * @var Section
     */
    private $section;

    /**
     * @setUp
     */
    protected function setUp()
    {
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
        $section = new Section();
        $products = $section->getProducts();
        $this->assertInstanceOf(ArrayCollection::class, $products);
    }
}
