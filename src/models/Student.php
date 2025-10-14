<?php

namespace Student;

class Instance
{
  public string $cpf;
  public string $first_name;
  public string $last_name;
  public string $full_name;
  public string $birth_date;
  public string $address;
  public string $ddd;
  public string $phone_number;
}

class Insert
{
  public function __construct(
    public string $cpf,
    public string $first_name,
    public string $last_name,
    public string $full_name,
    public string $birth_date,
    public string $address,
    public string $ddd,
    public string $phone_number,
  ) {}
}

class Update
{
  public function __construct(
    public ?string $first_name,
    public ?string $last_name,
    public ?string $full_name,
    public ?string $birth_date,
    public ?string $address,
    public ?string $ddd,
    public ?string $phone_number,
  ) {}
}

class Model
{
  public function __construct(protected \PDO $db) {}

  /**
   * @return Instance[]
   */
  public function getAllStudents(): array
  {
    $stmt = $this->db->prepare("SELECT * FROM student");
    $stmt->execute();
    $data = $stmt->fetchAll(\PDO::FETCH_CLASS, "Student\Instance");
    return $data;
  }

  public function getStudent(string $id): Instance
  {
    $stmt = $this->db->prepare("SELECT * FROM student WHERE cpf=?");
    $stmt->execute([$id]);
    $data = $stmt->fetch(\PDO::FETCH_CLASS, "Student\Instance");
    return $data;
  }

  public function insertStudent(Insert $insert)
  {

    $stmt = $this->db->prepare("INSERT INTO student (cpf, first_name, last_name, full_name, birth_date, address, ddd, phone_number) VALUES (:cpf, :first_name, :last_name, :full_name, :birth_date, :address, :ddd, :phone_number)");
    $stmt->execute(get_object_vars($insert));
    $data = $stmt->fetch();
    return $data;
  }

  public function modifyStudent(string $id, Update $updade)
  {
    $query = [];
    foreach ($updade as $key => $_) {
      $query[] = "$key = :$key";
    }
    $query = implode(", ", $query);
    $stmt = $this->db->prepare("UPDATE student SET $query WHERE cpf=:id");
    $stmt->execute(array_merge($updade, ["id" => $id]));
    $data = $stmt->fetch(\PDO::FETCH_CLASS, "Student\Instance");
    return $data;
  }

  public function deleteStudent(string $id): Instance
  {
    $stmt = $this->db->prepare("DELETE FROM student WHERE cpf=? RETURNING *");
    $stmt->execute([$id]);
    $data = $stmt->fetch(\PDO::FETCH_CLASS, "Student\Instance");
    return $data;
  }
}
