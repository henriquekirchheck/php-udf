<?php

namespace Student;

class Insert
{
  public function __construct(
    public string $cpf,
    public string $first_name,
    public string $last_name,
    public string $full_name,
    public string $birthDate,
    public string $address,
    public string $ddd,
    public string $phoneNumber,
  ) {}
}

class Update
{
  public function __construct(
    public ?string $first_name,
    public ?string $last_name,
    public ?string $full_name,
    public ?string $birthDate,
    public ?string $address,
    public ?string $ddd,
    public ?string $phoneNumber,
  ) {}
}

class Model
{
  public function __construct(protected \PDO $db) {}

  public function getAllStudents() {}
  public function getStudent(string $id) {}
  public function modifyStudent(string $id, Update $updade) {}
  public function deleteStudent(string $id) {}
}
