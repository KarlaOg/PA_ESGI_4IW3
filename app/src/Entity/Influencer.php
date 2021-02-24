<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\InfluencerRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=InfluencerRepository::class)
 * @UniqueEntity("userId")
 * @UniqueEntity("username", message="Ce pseudo est déjà utilisé")
 */
class Influencer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $socialNetwork = [
        'instagram' => '',
        'tiktok' => '',
        'facebook' => '',
        'youtube' => '',
        'twitter' => '',
        'twitch' => ''
    ];

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $siret;

    /**
     * @ORM\ManyToMany(targetEntity=Application::class, mappedBy="influencerId")
     */
    private $applications;

    /**
     * @ORM\OneToMany(targetEntity=Contract::class, mappedBy="influencerId")
     */
    private $brandId;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $username;

    /** 
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     */
    private $userId;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $type = [];

    public function __construct()
    {
        $this->applications = new ArrayCollection();
        $this->brandId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getSocialNetwork(): ?array
    {
        return $this->socialNetwork;
    }

    public function setSocialNetwork(array $socialNetwork): self
    {
        $this->socialNetwork = $socialNetwork;

        return $this;
    }

    public function getSiret(): ?int
    {
        return $this->siret;
    }

    public function setSiret(?int $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * @return Collection|Application[]
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(Application $application): self
    {
        if (!$this->applications->contains($application)) {
            $this->applications[] = $application;
            $application->addInfluencerId($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): self
    {
        if ($this->applications->removeElement($application)) {
            $application->removeInfluencerId($this);
        }

        return $this;
    }

    /**
     * @return Collection|Contract[]
     */
    public function getBrandId(): Collection
    {
        return $this->brandId;
    }

    public function addBrandId(Contract $brandId): self
    {
        if (!$this->brandId->contains($brandId)) {
            $this->brandId[] = $brandId;
            $brandId->setInfluencerId($this);
        }

        return $this;
    }

    public function removeBrandId(Contract $brandId): self
    {
        if ($this->brandId->removeElement($brandId)) {
            // set the owning side to null (unless already changed)
            if ($brandId->getInfluencerId() === $this) {
                $brandId->setInfluencerId(null);
            }
        }

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getType(): ?array
    {
        return $this->type;
    }

    public function setType(?array $type): self
    {
        $this->type = $type;

        return $this;
    }
}
