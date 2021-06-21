<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BrandRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass=BrandRepository::class)
 * @UniqueEntity("username", message="Ce pseudo est déjà utilisé")
 * @Vich\Uploadable
 */
class Brand
{
    const SERVER_PATH_TO_IMAGE_FOLDER = '/public/uploads';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="brand")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


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
    private $field = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

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
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Offer::class, mappedBy="brandId", orphanRemoval=true)
     */
    private $offers;

    public function __construct()
    {
        $this->offers = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getField(): ?array
    {
        return $this->field;
    }

    public function setField(?array $field): self
    {
        $this->field = $field;

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

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
        ));
    }

    public function __toString()
    {
        if (is_null($this->name)) {
            return 'NULL';
        }
        return $this->name;
    }

    /**
     * @return Collection|Offer[]
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setBrandId($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getBrandId() === $this) {
                $offer->setBrandId(null);
            }
        }

        return $this;
    }
}
