<?php

namespace App\Entity;

use App\Repository\InstagramUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstagramUserRepository::class)]
class InstagramUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $username;

    #[ORM\Column(type: 'string', length: 1000, nullable: true)]
    private $description;

    #[ORM\OneToMany(mappedBy: 'InstagramUser', targetEntity: Visual::class)]
    private $visuals;

    #[ORM\OneToMany(mappedBy: 'InstagramUser', targetEntity: Post::class)]
    private $posts;

    public function __construct()
    {
        $this->visuals = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Visual>
     */
    public function getVisuals(): Collection
    {
        return $this->visuals;
    }

    public function addVisual(Visual $visual): self
    {
        if (!$this->visuals->contains($visual)) {
            $this->visuals[] = $visual;
            $visual->setInstagramUser($this);
        }

        return $this;
    }

    public function removeVisual(Visual $visual): self
    {
        if ($this->visuals->removeElement($visual)) {
            // set the owning side to null (unless already changed)
            if ($visual->getInstagramUser() === $this) {
                $visual->setInstagramUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setInstagramUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getInstagramUser() === $this) {
                $post->setInstagramUser(null);
            }
        }

        return $this;
    }
}
