<?php

namespace App\Entity;

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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank 
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Brand::class)
     * @JoinColumn(onDelete="CASCADE")
     * @Assert\NotNull
     */
    private $brandId;

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
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Application::class, inversedBy="offerId")
     */
    private $application;

    /**
     * @ORM\Column(type="json")
     */
    private $field = [];

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
        return $this->dateEnd ;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): self
    {  
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getApplication(): ?Application
    {
        return $this->application;
    }

    public function setApplication(?Application $application): self
    {
        $this->application = $application;

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
}
