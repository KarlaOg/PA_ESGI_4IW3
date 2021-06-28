<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OfferRepository;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="text", length=65535)
     * @Assert\NotBlank
     */
    private $description;


    /**
     * @ORM\Column(type="datetimetz")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="datetimetz")
     *@Assert\GreaterThanOrEqual("tomorrow", message="Une offre peut commencer qu'à partir de demain.")
     *@Assert\GreaterThan(propertyPath="dateCreation", message="Une offre ne peut pas être inférieure à la date de publication.")
     *@Assert\LessThanOrEqual(propertyPath="dateEnd", message="La date de commencement doit être inférieure à la date de fin.")
     */
    private $dateStart;

    /**
     * @ORM\Column(type="datetimetz")
     *@Assert\GreaterThanOrEqual("tomorrow")
     *@Assert\GreaterThanOrEqual(propertyPath="dateStart", message="La date de fin doit être supérieure à la date de commencement.")
     * @Assert\Range(
     *      minPropertyPath = "dateStart",
     *      max = "+5 years"
     * )
     */
    private $dateEnd;


    /**
     * @ORM\Column(type="json")
     */
    private $field = [];


    /**
     * @ORM\ManyToOne(targetEntity=Brand::class, inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $brandId;

    /**
     * @ORM\OneToMany(targetEntity=Application::class, mappedBy="offer", orphanRemoval=true)
     */
    private $application;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="annonces", orphanRemoval=true)
     */
    private $comments;

    public function __construct()
    {
        $this->application = new ArrayCollection();
        $this->comments = new ArrayCollection();
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


    public function getBrandId(): ?Brand
    {
        return $this->brandId;
    }

    public function setBrandId(?Brand $brandId): self
    {
        $this->brandId = $brandId;

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

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setOffer($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getOffer() === $this) {
                $comment->setOffer(null);
            }
        }

        return $this;
    }
}
