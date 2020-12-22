<?php

namespace App\Entity;

use App\Repository\GaleryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GaleryRepository::class)
 */
class Galery
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileName1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileName2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileName3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileName4;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName1(): ?string
    {
        return $this->fileName1;
    }

    public function setFileName1(?string $fileName1): self
    {
        $this->fileName1 = $fileName1;

        return $this;
    }

    public function getFileName2(): ?string
    {
        return $this->fileName2;
    }

    public function setFileName2(?string $fileName2): self
    {
        $this->fileName2 = $fileName2;

        return $this;
    }

    public function getFileName3(): ?string
    {
        return $this->fileName3;
    }

    public function setFileName3(?string $fileName3): self
    {
        $this->fileName3 = $fileName3;

        return $this;
    }

    public function getFileName4(): ?string
    {
        return $this->fileName4;
    }

    public function setFileName4(?string $fileName4): self
    {
        $this->fileName4 = $fileName4;

        return $this;
    }
}
