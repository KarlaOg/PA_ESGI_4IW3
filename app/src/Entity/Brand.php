<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BrandRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=BrandRepository::class)
 * @UniqueEntity("username", message="Ce pseudo est déjà utilisé")
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
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type(
     *     type="integer",
     *     message="Vous ne pouvez pas mettre de lettre, mettez des chiffres"
     * )
     */
    private $siret;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $socialNetwork = [
        'Website' => '',
        'Instagram' => '',
        'Tiktok' => '',
        'Facebook' => '',
        'Youtube' => '',
        'Twitter' => '',
        'Twitch' => ''
    ];
    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $domaine = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Regex("/^[a-z0-9]+$/i", message="Vous ne pouvez pas mettre d'espace")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;


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


    public function getSiret(): ?int
    {
        return $this->siret;
    }

    public function setSiret(int $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSocialNetwork(): ?array
    {
        return $this->socialNetwork;
    }

    public function setSocialNetwork(?array $socialNetwork): self
    {
        $this->socialNetwork = $socialNetwork;

        return $this;
    }

    public function getDomaine(): ?array
    {
        return $this->domaine;
    }

    public function setDomaine(?array $domaine): self
    {
        $this->domaine = $domaine;

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

    public function __toString()
    {
        if (is_null($this->name)) {
            return 'NULL';
        }
        return $this->name;
    }
}
