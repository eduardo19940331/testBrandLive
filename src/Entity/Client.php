<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
// use App\Repository\ClientRepository;

/**
 * Client
 *
 * @ORM\Entity
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository") 
 */
class Client
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
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=50, nullable=false)
     */
    private $firstname = '';

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=50, nullable=false)
     */
    private $lastname = '';

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=150, nullable=false)
     */
    private $email = '';

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=300, nullable=false)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="enabled", type="integer", nullable=false, options={"default"="1"})
     */
    private $enabled = '1';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;


    /**
     * @ORM\OneToMany(targetEntity="ClientGroup", mappedBy="client")
     * @var \ClientGroup[]
     **/
    private $clientGroup;

    /**
     * Get clientGroup
     *
     * @return ClientGroup[]
     */
    public function getClientGroups()
    {
        return $this->clientGroup;
    }

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
     * Set lastname
     *
     * @param string $lastname
     */
    public function setLastName(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     */
    public function getLastName(): string
    {
        return $this->lastname;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     */
    public function setFirstName(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     */
    public function getFirstName(): string
    {
        return $this->firstname;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     */
    public function getEmail(): string
    {
        return $this->email;
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
     * @param DateTime $deletedAt
     */
    public function setDeleted(DateTime $deletedAt): self
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

    public function getFullName(): string
    {
        return $this->lastname . " " . $this->lastname;
    }
}
