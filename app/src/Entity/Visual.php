<?php

namespace App\Entity;

use App\Repository\VisualRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisualRepository::class)]
class Visual
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 1000)]
    private $path;

    #[ORM\ManyToOne(targetEntity: InstagramUser::class, inversedBy: 'visuals')]
    #[ORM\JoinColumn(nullable: false)]
    private $InstagramUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getInstagramUser(): ?InstagramUser
    {
        return $this->InstagramUser;
    }

    public function setInstagramUser(?InstagramUser $InstagramUser): self
    {
        $this->InstagramUser = $InstagramUser;

        return $this;
    }
}
