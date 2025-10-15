<?php

namespace Note;

class Instance
{
  public string $id;
  public string $title;
  public string $content;
  public string $created_at;
  public string $updated_at;

  public function __set($name, $value) {}
}

class Insert
{
  public function __construct(
    public string $title,
    public string $content,
  ) {}
}

class Update
{
  public function __construct(
    public ?string $title,
    public ?string $content,
  ) {}
}

class Model
{
  public function __construct(protected \PDO $db) {}

  /**
   * @return Instance[]
   */
  public function getAll(): array
  {
    $stmt = $this->db->prepare("SELECT * FROM notes");
    $stmt->execute();
    $data = $stmt->fetchAll(\PDO::FETCH_CLASS, "Note\Instance");
    return $data;
  }

  /**
   * @return Instance[]
   */
  public function search(string $query): array
  {
    $stmt = $this->db->prepare("SELECT id, content, title, updated_at, created_at, paradedb.score(id) FROM notes WHERE id @@@ paradedb.disjunction_max(ARRAY[paradedb.match('title', :query, distance => 0), paradedb.match('content', :query, distance => 0)]) ORDER BY score DESC");
    $stmt->execute(["query" => $query]);
    $data = $stmt->fetchAll(\PDO::FETCH_CLASS, "Note\Instance");
    return $data;
  }

  public function getOne(int $id): Instance
  {
    $stmt = $this->db->prepare("SELECT * FROM notes WHERE id=?");
    $stmt->execute([$id]);
    $stmt->setFetchMode(\PDO::FETCH_CLASS, "Note\Instance");
    $data = $stmt->fetch();
    return $data;
  }

  public function insert(Insert $insert)
  {

    $stmt = $this->db->prepare("INSERT INTO notes (title, content) VALUES (:title, :content)");
    $stmt->execute(get_object_vars($insert));
    $data = $stmt->fetch();
    return $data;
  }

  public function modify(int $id, Update $update)
  {
    $query = [];
    foreach ($update as $key => $_) {
      $query[] = "$key = :$key";
    }
    $query = implode(", ", $query);
    $stmt = $this->db->prepare("UPDATE notes SET $query WHERE id=:id");
    $stmt->execute(array_merge(get_object_vars($update), ["id" => $id]));
    $stmt->setFetchMode(\PDO::FETCH_CLASS, "Note\Instance");
    $data = $stmt->fetch();
    return $data;
  }

  public function delete(int $id): Instance
  {
    $stmt = $this->db->prepare("DELETE FROM notes WHERE id=? RETURNING *");
    $stmt->execute([$id]);
    $stmt->setFetchMode(\PDO::FETCH_CLASS, "Note\Instance");
    $data = $stmt->fetch();
    return $data;
  }
}
