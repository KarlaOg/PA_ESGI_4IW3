<?php

namespace App\Entity;

use App\Repository\InfluencerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InfluencerRepository::class)
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="influencers")
     */
    private $userId;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $socialNetwork = [];

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

    public function __construct()
    {
        $this->applications = new ArrayCollection();
        $this->brandId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?bool
    {
        return $this->type;
    }

    public function setType(bool $type): self
    {
        $this->type = $type;

        return $this;
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
}
