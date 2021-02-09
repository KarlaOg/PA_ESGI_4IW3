<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="user_account")
 * @UniqueEntity("email")
 * @Vich\Uploadable
 */
class User implements UserInterface, \Serializable
{


    const SERVER_PATH_TO_IMAGE_FOLDER = '/public/uploads';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email()
     * @Assert\NotBlank(message="email.validate.bd")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $age;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $type = [];

    /**
     * @ORM\OneToMany(targetEntity=Influencer::class, mappedBy="userId")
     */
    private $influencers;

    /**
     * @ORM\OneToMany(targetEntity=Brand::class, mappedBy="UserId")
     */
    private $brands;

    /**
     * @ORM\ManyToMany(targetEntity=Payment::class, mappedBy="userId")
     */
    private $payments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nombreAbonnes;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $liens = [];


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageUser;

    /**
     * @Vich\UploadableField(mapping="cover_image_user", fileNameProperty="imageUser")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", options={ "default": "NOW()" })
     * @var \DateTime
     */
    private $updatedAt;


    // Pour les test unitaire (pas complet)
    public function isValid(): bool
    {
        return !empty($this->firstname)
            && !empty($this->lastname)
            && !empty($this->password)
            && !empty($this->age)
            && strlen($this->password) >= 3
            && strlen($this->password) <= 50
            && !empty($this->email)
            && filter_var($this->email, FILTER_VALIDATE_EMAIL);
        //&& Carbon::now()->subYears(10)->isAfter($this->age);
    }

    public function __construct()
    {
        $this->influencers = new ArrayCollection();
        $this->brands = new ArrayCollection();
        $this->payments = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        // $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAge(): ?\DateTimeInterface
    {
        return $this->age;
    }

    public function setAge(\DateTimeInterface $age): self
    {
        $this->age = $age;

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

    /**
     * @return Collection|Influencer[]
     */
    public function getInfluencers(): Collection
    {
        return $this->influencers;
    }

    public function addInfluencer(Influencer $influencer): self
    {
        if (!$this->influencers->contains($influencer)) {
            $this->influencers[] = $influencer;
            $influencer->setUserId($this);
        }

        return $this;
    }

    public function removeInfluencer(Influencer $influencer): self
    {
        if ($this->influencers->removeElement($influencer)) {
            // set the owning side to null (unless already changed)
            if ($influencer->getUserId() === $this) {
                $influencer->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Brand[]
     */
    public function getBrands(): Collection
    {
        return $this->brands;
    }

    public function addBrand(Brand $brand): self
    {
        if (!$this->brands->contains($brand)) {
            $this->brands[] = $brand;
            $brand->setUserId($this);
        }

        return $this;
    }

    public function removeBrand(Brand $brand): self
    {
        if ($this->brands->removeElement($brand)) {
            // set the owning side to null (unless already changed)
            if ($brand->getUserId() === $this) {
                $brand->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Payment[]
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments[] = $payment;
            $payment->addUserId($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->removeElement($payment)) {
            $payment->removeUserId($this);
        }

        return $this;
    }

    public function getNombreAbonnes(): ?string
    {
        return $this->nombreAbonnes;
    }

    public function setNombreAbonnes(string $nombreAbonnes): self
    {
        $this->nombreAbonnes = $nombreAbonnes;

        return $this;
    }

    public function getLiens(): ?array
    {
        return $this->liens;
    }

    public function setLiens(array $liens): self
    {
        $this->liens = $liens;

        return $this;
    }



    public function getImageUser(): ?string
    {
        return $this->imageUser;
    }

    public function setImageUser(?string $imageUser): self
    {
        $this->imageUser = $imageUser;

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

    public function unserialize($serialized)
    {

        list(
            $this->id,
            $this->email,
            $this->password,
        ) = unserialize($serialized);
    }

    public function __toString()
    {
        return (string) $this->id;
    }
}
