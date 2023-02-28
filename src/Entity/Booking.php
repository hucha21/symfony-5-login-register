<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $booking_description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $booked_from = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $booked_until = null;

    #[ORM\Column]
    private ?int $room = null;

    #[ORM\Column]
    private ?int $user_id = null;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookingDescription(): ?string
    {
        return $this->booking_description;
    }

    public function setBookingDescription(?string $booking_description): self
    {
        $this->booking_description = $booking_description;

        return $this;
    }

    public function getBookedFrom(): ?\DateTimeInterface
    {
        return $this->booked_from;
    }

    public function setBookedFrom(\DateTimeInterface $booked_from): self
    {
        $this->booked_from = $booked_from;

        return $this;
    }

    public function getBookedUntil(): ?\DateTimeInterface
    {
        return $this->booked_until;
    }

    public function setBookedUntil(\DateTimeInterface $booked_until): self
    {
        $this->booked_until = $booked_until;

        return $this;
    }

    public function getRoom(): ?int
    {
        return $this->room;
    }

    public function setRoom(int $room): self
    {
        $this->room = $room;

        return $this;
    }
    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }
}
