<?php

namespace Hyperion\Doctrine\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Link
 * @package Hyperion\Doctrine\Entity
 * @ORM\Entity()
 * @ORM\Table(name="links")
 */
class Link
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", name="link_id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", name="link_url")
     */
    private string $url;

    /**
     * @ORM\Column(type="string", name="link_name")
     */
    private string $name;

    /**
     * @ORM\Column(type="string", name="link_image")
     */
    private string $image;

    /**
     * @ORM\Column(type="string", name="link_target")
     */
    private string $target;

    /**
     * @ORM\Column(type="string", name="link_description")
     */
    private string $description;

    /**
     * @ORM\Column(type="string", name="link_visible")
     */
    private string $visible;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="links")
     * @ORM\JoinColumn(name="link_owner", referencedColumnName="ID")
     */
    private User $owner;

    /**
     * @ORM\Column(type="integer", name="link_rating")
     */
    private int $rating;

    /**
     * @ORM\Column(type="datetime", name="link_updated")
     */
    private DateTime $updated;

    /**
     * @ORM\Column(type="string", name="link_rel")
     */
    private string $rel;

    /**
     * @ORM\Column(type="string", name="link_notes")
     */
    private string $notes;

    /**
     * @ORM\Column(type="string", name="link_rss")
     */
    private string $rss;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Link
     */
    public function setId(int $id): Link
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Link
     */
    public function setUrl(string $url): Link
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Link
     */
    public function setName(string $name): Link
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     * @return Link
     */
    public function setImage(string $image): Link
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @param string $target
     * @return Link
     */
    public function setTarget(string $target): Link
    {
        $this->target = $target;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Link
     */
    public function setDescription(string $description): Link
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->visible === 'Y';
    }

    /**
     * @param bool $visible
     * @return Link
     */
    public function setVisible(bool $visible): Link
    {
        $this->visible = $visible ? 'Y' : 'N';
        return $this;
    }

    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     * @return Link
     */
    public function setOwner(User $owner): Link
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     * @return Link
     */
    public function setRating(int $rating): Link
    {
        $this->rating = $rating;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdated(): DateTime
    {
        return $this->updated;
    }

    /**
     * @param DateTime $updated
     * @return Link
     */
    public function setUpdated(DateTime $updated): Link
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * @return string
     */
    public function getRel(): string
    {
        return $this->rel;
    }

    /**
     * @param string $rel
     * @return Link
     */
    public function setRel(string $rel): Link
    {
        $this->rel = $rel;
        return $this;
    }

    /**
     * @return string
     */
    public function getNotes(): string
    {
        return $this->notes;
    }

    /**
     * @param string $notes
     * @return Link
     */
    public function setNotes(string $notes): Link
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * @return string
     */
    public function getRss(): string
    {
        return $this->rss;
    }

    /**
     * @param string $rss
     * @return Link
     */
    public function setRss(string $rss): Link
    {
        $this->rss = $rss;
        return $this;
    }
}
