<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     *      minMessage = "El nombre debe tener un minimo {{ limit }} caracteres",
     *      maxMessage = "El nombre debe tener un maximo {{ limit }} caracteres"
     * )
     */
    private $firstname = '';

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=50, nullable=false)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     *      minMessage = "El apellido debe tener un minimo {{ limit }} caracteres",
     *      maxMessage = "El apellido debe tener un maximo {{ limit }} caracteres"
     * )
     */
    private $lastname = '';

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=150, nullable=false)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 5,
     *      max = 150,
     *      minMessage = "El email debe tener un minimo {{ limit }} caracteres",
     *      maxMessage = "El email debe tener un maximo {{ limit }} caracteres"
     * )
     * @Assert\Email
     */
    private $email = '';

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=300, nullable=false)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 5,
     *      max = 150,
     *      minMessage = "La descripción debe tener un minimo {{ limit }} caracteres",
     *      maxMessage = "La descripción debe tener un maximo {{ limit }} caracteres"
     * )
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
     * @ORM\OneToMany(targetEntity="ClientGroup", mappedBy="client", cascade={"persist"})
     *  @var ArrayCollection<int,ClientGroup>
     **/
    private $clientGroup;

    /**
     * Get clientGroup
     *
     * @return ClientGroup[]
     */
    public function getClientGroups()
    {
        $data = [];
        $clientGroup = $this->clientGroup !== null ? $this->clientGroup->toArray() : [];
        foreach ($clientGroup as $cg) {
            $data[] = $cg;
        }
        return $data;
    }

    /**
     * Get clientGroup
     *
     * @return ClientGroup[]
     */
    public function getClientGroup()
    {
        $data = [];
        $clientGroup = $this->clientGroup !== null ? $this->clientGroup->toArray() : [];
        foreach ($clientGroup as $cg) {
            if ($cg->getEnabled() == 1 && $cg->getGroup()->getEnabled() == 1) {
                $data[] = $cg->getGroup();
            }
        }
        return $data;
    }

    public function setClientGroup($clientGroups)
    {
        $groupsExist = [];
        foreach ($this->getClientGroups() as $clientGroupExist) {
            $clientGroupExist->setEnabled(1);
            $clientGroupExist->setDeleted(null);
            $this->clientGroup[] = $clientGroupExist;
            $groupsExist[$clientGroupExist->getGroup()->getId()] =
                $clientGroupExist->getGroup()->getId();
        }
        foreach ($clientGroups as $cg) {
            if (!in_array($cg->getId(), $groupsExist)) {
                $ClientGroupEntity = new ClientGroup();
                $ClientGroupEntity->setCreated(new DateTime());
                $ClientGroupEntity->setClient($this);
                $ClientGroupEntity->setGroup($cg);
                $ClientGroupEntity->setEnabled(1);

                $this->clientGroup[] = $ClientGroupEntity;
            }
            if (in_array($cg->getId(), $groupsExist)) {
                unset($groupsExist[$cg->getId()]);
            }
        }
        if ($groupsExist) {
            foreach ($this->getClientGroups() as $clientGroupExist) {
                if (in_array($clientGroupExist->getGroup()->getId(), $groupsExist)) {
                    $clientGroupExist->setEnabled(0);
                    $clientGroupExist->setDeleted(new DateTime());
                    $this->clientGroup[] = $clientGroupExist;
                }
            }
        }

        return $this;
    }

    /**
     * Set id
     * 
     * @return int|null
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     * 
     * @return int|null
     */
    public function getId()
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
     * 
     * @return string|null
     */
    public function getDescription()
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
    public function setEnabled($enabled = 1): self
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
