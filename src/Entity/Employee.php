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
    private int|null $id = null;

    #[ORM\Column(length: 255)]
    private string|null $first_name = null;

    #[ORM\Column(length: 255)]
    private string|null $last_name = null;

    #[ORM\Column]
    private int|null $age;

    #[ORM\Column(nullable: true)]
    private array|null $hobby = null;

    #[ORM\Column(enumType: Gender::class)]
    private Gender|null $gender = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private string|null $about_me = null;

    #[ORM\Column]
    private float|null $salary = null;

    #[ORM\Column(enumType: EmployeeRole::class)]
    private EmployeeRole|null $roles = null;

    #[ORM\Column(length: 255, nullable: true)]
    private string|null $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private string|null $profile_image = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface|null $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface|null $updated_at = null;

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getFirstName(): string|null
    {
        return $this->first_name;
    }

    public function setFirstName(string|null $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): string|null
    {
        return $this->last_name;
    }

    public function setLastName(string|null $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getAge(): int|null
    {
        return $this->age;
    }

    public function setAge(int|null $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getHobby(): ?array
    {
        return array_map(fn($hobby) => Hobby::tryFrom($hobby), $this->hobby ?? []);
    }

    public function getHobbyNames(): string|null
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

    public function getGender(): Gender|null
    {
        return $this->gender;
    }

    public function setGender(Gender|null $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getAboutMe(): string|null
    {
        return $this->about_me;
    }

    public function setAboutMe(string|null $about_me): static
    {
        $this->about_me = $about_me;

        return $this;
    }

    public function getSalary(): float|null
    {
        return $this->salary;
    }

    public function setSalary(float|null $salary): static
    {
        $this->salary = $salary;

        return $this;
    }

    public function getRoles(): EmployeeRole|null
    {
        return $this->roles;
    }

    public function setRoles(EmployeeRole|null $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getCity(): string|null
    {
        return $this->city;
    }

    public function setCity(string|null $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getProfileImage(): string|null
    {
        return "/uploads/profile_images/" . ($this->profile_image ?? "avatar.png");
    }

    public function setProfileImage(string|null $profile_image): static
    {
        $this->profile_image = $profile_image;

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface|null
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface|null $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): \DateTimeInterface|null
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface|null $updated_at): static
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
