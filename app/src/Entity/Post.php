<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 10000, nullable: true)]
    private $text;

    #[ORM\OneToMany(mappedBy: 'Post', targetEntity: PostVisual::class)]
    private $postVisuals;

    #[ORM\ManyToOne(targetEntity: InstagramUser::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private $InstagramUser;

    public function __construct()
    {
        $this->postVisuals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return Collection<int, PostVisual>
     */
    public function getPostVisuals(): Collection
    {
        return $this->postVisuals;
    }

    public function addPostVisual(PostVisual $postVisual): self
    {
        if (!$this->postVisuals->contains($postVisual)) {
            $this->postVisuals[] = $postVisual;
            $postVisual->setPost($this);
        }

        return $this;
    }

    public function removePostVisual(PostVisual $postVisual): self
    {
        if ($this->postVisuals->removeElement($postVisual)) {
            // set the owning side to null (unless already changed)
            if ($postVisual->getPost() === $this) {
                $postVisual->setPost(null);
            }
        }

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
