<?php

namespace App\Entity;

use App\Repository\ApplicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApplicationRepository::class)
 */
class Application
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Influencer::class, inversedBy="applications")
     */
    private $influencerId;

    /**
     * @ORM\OneToMany(targetEntity=Offer::class, mappedBy="application")
     */
    private $offerId;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=Brand::class, mappedBy="application")
     */
    private $brandId;

    public function __construct()
    {
        $this->influencerId = new ArrayCollection();
        $this->offerId = new ArrayCollection();
        $this->brandId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Influencer[]
     */
    public function getInfluencerId(): Collection
    {
        return $this->influencerId;
    }

    public function addInfluencerId(Influencer $influencerId): self
    {
        if (!$this->influencerId->contains($influencerId)) {
            $this->influencerId[] = $influencerId;
        }

        return $this;
    }

    public function removeInfluencerId(Influencer $influencerId): self
    {
        $this->influencerId->removeElement($influencerId);

        return $this;
    }

    /**
     * @return Collection|Offer[]
     */
    public function getOfferId(): Collection
    {
        return $this->offerId;
    }

    public function addOfferId(Offer $offerId): self
    {
        if (!$this->offerId->contains($offerId)) {
            $this->offerId[] = $offerId;
            $offerId->setApplication($this);
        }

        return $this;
    }

    public function removeOfferId(Offer $offerId): self
    {
        if ($this->offerId->removeElement($offerId)) {
            // set the owning side to null (unless already changed)
            if ($offerId->getApplication() === $this) {
                $offerId->setApplication(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Brand[]
     */
    public function getBrandId(): Collection
    {
        return $this->brandId;
    }

    public function addBrandId(Brand $brandId): self
    {
        if (!$this->brandId->contains($brandId)) {
            $this->brandId[] = $brandId;
            $brandId->setApplication($this);
        }

        return $this;
    }

    public function removeBrandId(Brand $brandId): self
    {
        if ($this->brandId->removeElement($brandId)) {
            // set the owning side to null (unless already changed)
            if ($brandId->getApplication() === $this) {
                $brandId->setApplication(null);
            }
        }

        return $this;
    }
}
