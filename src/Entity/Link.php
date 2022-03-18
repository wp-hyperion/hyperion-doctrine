<?php

namespace Hyperion\Doctrine\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Link
 * @package Hyperion\Doctrine\Entity
 * @ORM\Entity()
 * @ORM\Table(name="links",indexes={@ORM\Index(name="link_visible_idx", columns={"link_visible"})})
 */
class Link
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", name="link_id", options={"unsigned" : true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", name="link_url")
     */
    private string $url;

    /**
     * @ORM\Column(type="string", name="link_name", nullable=true)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", name="link_image", nullable=true)
     */
    private ?string $image = null;

    /**
     * @ORM\Column(type="string", name="link_target", nullable=true)
     */
    private ?string $target = null;

    /**
     * @ORM\Column(type="string", name="link_description", nullable=true)
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="boolean", name="link_visible", options={"default": true})
     */
    private bool $visible = true;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="links")
     * @ORM\JoinColumn(name="link_owner", referencedColumnName="ID")
     */
    private User $owner;

    /**
     * @ORM\Column(type="integer", name="link_rating", nullable=true)
     */
    private ?int $rating = null;

    /**
     * @ORM\Column(type="datetime", name="link_updated", nullable=true)
     */
    private ?DateTime $updated = null;

    /**
     * @ORM\Column(type="string", name="link_rel", nullable=true)
     */
    private ?string $rel = null;

    /**
     * @ORM\Column(type="string", name="link_notes", nullable=true)
     */
    private ?string $notes = null;

    /**
     * @ORM\Column(type="string", name="link_rss", nullable=true)
     */
    private ?string $rss = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getTarget(): ?string
    {
        return $this->target;
    }

    public function setTarget(?string $target): void
    {
        $this->target = $target;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): void
    {
        $this->visible = $visible;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): void
    {
        $this->owner = $owner;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): void
    {
        $this->rating = $rating;
    }

    public function getUpdated(): ?DateTime
    {
        return $this->updated;
    }

    public function setUpdated(?DateTime $updated): void
    {
        $this->updated = $updated;
    }

    public function getRel(): ?string
    {
        return $this->rel;
    }

    public function setRel(?string $rel): void
    {
        $this->rel = $rel;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): void
    {
        $this->notes = $notes;
    }

    public function getRss(): ?string
    {
        return $this->rss;
    }

    public function setRss(?string $rss): void
    {
        $this->rss = $rss;
    }
}
