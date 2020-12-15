<?php

namespace App\Entity;

use App\Repository\BrandRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BrandRepository::class)
 */
class Brand
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="brands")
     */
    private $UserId;

    /**
     * @ORM\Column(type="array")
     */
    private $domain = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $typeBrand;

    /**
     * @ORM\Column(type="integer")
     */
    private $siret;

    /**
     * @ORM\ManyToOne(targetEntity=Application::class, inversedBy="brandId")
     */
    private $application;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->UserId;
    }

    public function setUserId(?User $UserId): self
    {
        $this->UserId = $UserId;

        return $this;
    }

    public function getDomain(): ?array
    {
        return $this->domain;
    }

    public function setDomain(array $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function getTypeBrand(): ?bool
    {
        return $this->typeBrand;
    }

    public function setTypeBrand(bool $typeBrand): self
    {
        $this->typeBrand = $typeBrand;

        return $this;
    }

    public function getSiret(): ?int
    {
        return $this->siret;
    }

    public function setSiret(int $siret): self
    {
        $this->siret = $siret;

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
}
