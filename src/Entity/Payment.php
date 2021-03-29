<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaymentRepository::class)
 */
class Payment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="payments")
     */
    private $userId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $payment_ref;

    /**
     * @ORM\OneToOne(targetEntity=Offer::class, cascade={"persist", "remove"})
     */
    private $offerId;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $dateCreation;

    public function __construct()
    {
        $this->userId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserId(): Collection
    {
        return $this->userId;
    }

    public function addUserId(User $userId): self
    {
        if (!$this->userId->contains($userId)) {
            $this->userId[] = $userId;
        }

        return $this;
    }

    public function removeUserId(User $userId): self
    {
        $this->userId->removeElement($userId);

        return $this;
    }

    public function getPaymentRef(): ?int
    {
        return $this->payment_ref;
    }

    public function setPaymentRef(?int $payment_ref): self
    {
        $this->payment_ref = $payment_ref;

        return $this;
    }

    public function getOfferId(): ?Offer
    {
        return $this->offerId;
    }

    public function setOfferId(?Offer $offerId): self
    {
        $this->offerId = $offerId;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }
}
