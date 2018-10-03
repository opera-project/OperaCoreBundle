<?php

namespace Opera\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Opera\CoreBundle\Repository\PageRepository")
 */
class Page
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $configuration;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $access_level = 'free';

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $status;

    /**
     * @ORM\Column(type="text",  nullable=true)
     */
    private $meta_keyword;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $meta_description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $complementary_meta;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $rel_meta;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $route;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="Opera\CoreBundle\Entity\Layout", inversedBy="pages")
     * @ORM\JoinColumn(referencedColumnName="name", name="layout_name", nullable=false, onDelete="CASCADE")
     */
    private $layout;

    /**
     * @ORM\OneToMany(targetEntity="Opera\CoreBundle\Entity\Block", mappedBy="page", orphanRemoval=true)
     */
    private $blocks;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_regexp = false;

    public function __construct()
    {
        $this->blocks = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getConfiguration()
    {
        return $this->configuration;
    }

    public function setConfiguration($configuration): self
    {
        $this->configuration = $configuration;

        return $this;
    }

    public function getAccessLevel(): ?string
    {
        return $this->access_level;
    }

    public function setAccessLevel(string $access_level): self
    {
        $this->access_level = $access_level;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getMetaKeyword(): ?string
    {
        return $this->meta_keyword;
    }

    public function setMetaKeyword(?string $meta_keyword): self
    {
        $this->meta_keyword = $meta_keyword;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->meta_description;
    }

    public function setMetaDescription(?string $meta_description): self
    {
        $this->meta_description = $meta_description;

        return $this;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(?string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getLayout(): ?Layout
    {
        return $this->layout;
    }

    public function setLayout(?Layout $layout): self
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * @return Collection|Block[]
     */
    public function getBlocks(): Collection
    {
        return $this->blocks;
    }

    public function addBlock(Block $block): self
    {
        if (!$this->blocks->contains($block)) {
            $this->blocks[] = $block;
            $block->setPage($this);
        }

        return $this;
    }

    public function removeBlock(Block $block): self
    {
        if ($this->blocks->contains($block)) {
            $this->blocks->removeElement($block);
            // set the owning side to null (unless already changed)
            if ($block->getPage() === $this) {
                $block->setPage(null);
            }
        }

        return $this;
    }

    public function getIsRegexp(): bool
    {
        return $this->is_regexp;
    }

    public function setIsRegexp(bool $is_regexp): self
    {
        $this->is_regexp = $is_regexp;

        return $this;
    }

    public function getComplementaryMeta(): ?string
    {
        return $this->complementary_meta;
    }

    public function setComplementaryMeta(string $complementary_meta): self
    {
        $this->complementary_meta = $complementary_meta;

        return $this;
    }

    public function getRelMeta(): ?string
    {
        return $this->complementary_meta;
    }

    public function setRelMeta(string $complementary_meta): self
    {
        $this->complementary_meta = $complementary_meta;

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}
