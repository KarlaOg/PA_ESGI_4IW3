<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChatRepository::class)
 */
class Chat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $senderId;

    /**
     * @ORM\Column(type="integer")
     */
    private $receiverId;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $time;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSenderId(): ?int
    {
        return $this->senderId;
    }

    public function setSenderId(int $senderId): self
    {
        $this->senderId = $senderId;

        return $this;
    }

    public function getReceiverId(): ?int
    {
        return $this->receiverId;
    }

    public function setReceiverId(int $receiverId): self
    {
        $this->receiverId = $receiverId;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $memessage;

        return $this;
    }
}
