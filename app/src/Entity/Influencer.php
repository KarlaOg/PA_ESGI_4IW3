<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\InfluencerRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=InfluencerRepository::class)
 * @UniqueEntity("username", message="Ce pseudo est déjà utilisé")
 * @Vich\Uploadable
 */
class Influencer
{
    const SERVER_PATH_TO_IMAGE_FOLDER = '/public/uploads';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


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
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type(
     *     type="integer",
     *     message="Vous ne pouvez pas mettre de lettre, mettez des chiffres"
     * )
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

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Regex("/^[a-z0-9]+$/i", message="Vous ne pouvez pas mettre d'espace")
     */
    private $username;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $type = [];

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     */
    private $userId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profilePhoto;

    /**
     * @Vich\UploadableField(mapping="cover_image_user", fileNameProperty="profilePhoto")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", options={ "default": "NOW()" }, nullable=true)
     * @var \DateTime
     */
    private $updatedAt;

    public function __construct()
    {
        $this->applications = new ArrayCollection();
        $this->brandId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getType(): ?array
    {
        return $this->type;
    }

    public function setType(?array $type): self
    {
        $this->type = $type;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getProfilePhoto(): ?string
    {
        return $this->profilePhoto;
    }

    public function setProfilePhoto(?string $profilePhoto): self
    {
        $this->profilePhoto = $profilePhoto;

        return $this;
    }

    /**
     * @param null|File $imageFile
     * @return User
     * @throws Exception
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
        // permet a vich de savoir si l'image est nouvelle ou pas.
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
