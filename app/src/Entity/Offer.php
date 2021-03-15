<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OfferRepository;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=OfferRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Offer
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Brand::class)
     * @JoinColumn(onDelete="CASCADE")
     */
    private $brandId;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $dateStart;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $dateEnd;

    /**
     * @ORM\Column(type="json")
     */
    private $field = [];

    /**
     * @ORM\OneToMany(targetEntity=Application::class, mappedBy="offer")
     */
    private $application;

    public function __construct()
    {
        $this->application = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    /**
     * Gets triggered only on insert

     * @ORM\PrePersist
     */
    public function setDateCreation(): void
    {
        $this->dateCreation = new \DateTime("now");
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }


    public function getField(): ?array
    {
        return $this->field;
    }

    public function setField(array $field): self
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @return Collection|Application[]
     */
    public function getApplication(): Collection
    {
        return $this->application;
    }

    public function addApplication(Application $application): self
    {
        if (!$this->application->contains($application)) {
            $this->application[] = $application;
            $application->setOffer($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): self
    {
        if ($this->application->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getOffer() === $this) {
                $application->setOffer(null);
            }
        }

        return $this;
    }
}
