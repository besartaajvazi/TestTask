<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: DepartmentRepository::class)]
class Department
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private $id = null;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
    }
    
    /**
     * @ORM\OneToMany(targetEntity="Employee", mappedBy="department")
     */
    private $employees;

    #[ORM\Column(length: 255)]
    private ?string $department_name = null;

    #[ORM\Column(length: 255)]
    private ?string $department_leader = null;

    #[ORM\Column(length: 255)]
    private ?string $department_phone = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id ?? '';
        return $this;
    }

    public function getDepartmentName(): ?string
    {
        return $this->department_name;
    }

    public function setDepartmentName(?string $department_name): self
    {
        $this->department_name = $department_name ?? '';

        return $this;
    }

    public function getDepartmentLeader(): ?string
    {
        return $this->department_leader;
    }

    public function setDepartmentLeader(?string $department_leader): self
    {
        $this->department_leader = $department_leader ?? '';

        return $this;
    }

    public function getDepartmentPhone(): ?string
    {
        return $this->department_phone;
    }

    public function setDepartmentPhone(?string $department_phone): self
    {
        $this->department_phone = $department_phone ?? '';

        return $this;
    }
    
    public function getEmployees()
    {
        return $this->employees;
    }
}
