<?php

namespace App\Entity;

use App\Repository\SettingsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SettingsRepository::class)
 */
class Settings
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json")
     */
    private $item = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItem(): ?array
    {
        return $this->item;
    }

    public function setItem(array $item): self
    {
        $this->item = $item;

        return $this;
    }
}
