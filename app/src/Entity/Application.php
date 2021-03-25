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

     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Offer::class, inversedBy="application")
     */
    private $offer;


    public function __construct()
    {
        $this->influencerId = new ArrayCollection();
    }


    /**
     * toString
     * @return string
     */
/*    public function __toString()
    {
        if(is_null($this->influencerId)){
            return 'NULL';
        }
        return  $this->influencerId;

    }*/


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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(?Offer $offer): self
    {
        $this->offer = $offer;

        return $this;
    }

}
