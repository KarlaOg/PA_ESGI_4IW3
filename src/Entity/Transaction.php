<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $price;

    /**
     * @ORM\OneToOne(targetEntity=Offer::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $offerId;

    /**
     * @ORM\ManyToOne(targetEntity=Brand::class, inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $brandId;

    /**
     * @ORM\ManyToOne(targetEntity=Influencer::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $influencerId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getOfferId(): ?offer
    {
        return $this->offerId;
    }

    public function setOfferId(Offer $offerId): self
    {
        $this->offerId = $offerId;

        return $this;
    }

    public function getBrandId(): ?Brand
    {
        return $this->brandId;
    }

    public function setBrandId(?Brand $brandId): self
    {
        $this->brandId = $brandId;

        return $this;
    }

    public function getInfluencerId(): ?Influencer
    {
        return $this->influencerId;
    }

    public function setInfluencerId(?Influencer $influencerId): self
    {
        $this->influencerId = $influencerId;

        return $this;
    }
}
