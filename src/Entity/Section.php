<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Section
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $volume;

    /**
     * @ORM\Column(type="integer")
     */
    private $temperature;

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int) $this->id;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->name;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get volume.
     *
     * @return int
     */
    public function getVolume(): int
    {
        return (int) $this->volume;
    }

    /**
     * Set volume.
     *
     * @param integer $volume
     *
     * @return self
     */
    public function setVolume(int $volume): self
    {
        $this->volume = $volume;

        return $this;
    }

    /**
     * Get temperature.
     *
     * @return int
     */
    public function getTemperature(): int
    {
        return (int) $this->temperature;
    }

    /**
     * Set temperature.
     *
     * @param integer $temperature
     *
     * @return self
     */
    public function setTemperature(int $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }
}
