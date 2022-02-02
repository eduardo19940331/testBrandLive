<?php

namespace App\Entity;

use DateTime;
use App\Entity\Client;
use App\Entity\Group;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\GroupCategory;

/**
 * ClientGroup
 *
 * @ORM\Table(name="client_group", indexes={@ORM\Index(name="FK1_CLIENT_ID_CLIENT_GROUP", columns={"client_id"}), @ORM\Index(name="FK2_GROUP_ID_CLIENT_GROUP", columns={"group_id"})})
 * @ORM\Entity
 */
class ClientGroup
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var int
     *
     * @ORM\Column(name="enabled", type="integer", nullable=false)
     */
    private $enabled;

    /**
     * @var \Client
     *
     * @ORM\ManyToOne(targetEntity="Client" , inversedBy="clientGroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     * })
     */
    private $client;

    /**
     * @var \GroupCategory
     *
     * @ORM\ManyToOne(targetEntity="GroupCategory", inversedBy="clientGroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     * })
     */
    private $group;


    /**
     * Get id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     */
    public function setCreated(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     */
    public function getCreated(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param DateTime $updatedAt
     */
    public function setUpdated(DateTime $updatedAt): self
    {
        $this->createdAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     */
    public function getUpdated(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Set client
     *
     * @param Client $client
     */
    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Set group
     *
     * @param GroupCategory $client
     */
    public function setGroup(GroupCategory $group): self
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     */
    public function getGroup(): GroupCategory
    {
        return $this->group;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     */
    public function setEnabled($enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     */
    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Set deletedAt
     *
     * @param DateTime|null $deletedAt
     */
    public function setDeleted($deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     */
    public function getDeleted(): DateTime
    {
        return $this->deletedAt;
    }
}
