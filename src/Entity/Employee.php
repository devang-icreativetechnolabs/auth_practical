<?php

namespace App\Entity;

use App\Enum\EmployeeRole;
use App\Enum\Gender;
use App\Enum\Hobby;
use App\Repository\EmployeeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[HasLifecycleCallbacks]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    private ?string $last_name = null;

    #[ORM\Column]
    private ?int $age = null;

    #[ORM\Column(nullable: true)]
    private ?array $hobby = null;

    #[ORM\Column(enumType: Gender::class)]
    private ?Gender $gender = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $about_me = null;

    #[ORM\Column]
    private ?float $salary = null;

    #[ORM\Column(enumType: EmployeeRole::class)]
    private ?EmployeeRole $roles = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profile_image = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getHobby(): ?array
    {
        return array_map(fn($hobby) => Hobby::tryFrom($hobby), $this->hobby ?? []);
    }

    public function getHobbyNames(): ?string
    {
        if (!$this->hobby) {
            return null;
        }

        $hobbyNames = [];
        foreach ($this->hobby as $hobbyValue) {
            $hobby = Hobby::from($hobbyValue);
            $hobbyNames[] = $hobby->name;
        }
        return implode(', ', $hobbyNames);
    }

    public function setHobby(?array $hobby): static
    {
        $this->hobby = $hobby;

        return $this;
    }

    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    public function setGender(Gender $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getAboutMe(): ?string
    {
        return $this->about_me;
    }

    public function setAboutMe(?string $about_me): static
    {
        $this->about_me = $about_me;

        return $this;
    }

    public function getSalary(): ?float
    {
        return $this->salary;
    }

    public function setSalary(float $salary): static
    {
        $this->salary = $salary;

        return $this;
    }

    public function getRoles(): ?EmployeeRole
    {
        return $this->roles;
    }

    public function setRoles(EmployeeRole $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getProfileImage(): ?string
    {
        return $this->profile_image ? "/uploads/profile_images/" . $this->profile_image : null;
    }

    public function setProfileImage(?string $profile_image): static
    {
        $this->profile_image = $profile_image;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->created_at = new \DateTimeImmutable();
        $this->setUpdatedAtValue();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updated_at = new \DateTimeImmutable();
    }
}
