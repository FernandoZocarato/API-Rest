<?php

class TituloGateway
{
  private PDO $conn;

  public function __construct(Database $database)
  {
    $this->conn = $database->getConnection();
  }

  public function getAll(): array
  {
    $sql = "SELECT * FROM tarefas";

    $stmt = $this->conn->query($sql);

    $data = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
    {
      $data[] = $row;
    }
     
    return $data;
  }

  public function create(array $data): string
  {
    $sql = "INSERT INTO tarefas (titulo, dever) VALUE (:titulo, :dever)";

    $stmt = $this->conn->prepare($sql);

    $stmt->bindValue(":titulo", $data["titulo"], PDO::PARAM_STR);
    $stmt->bindValue(":dever", $data["dever"], PDO::PARAM_STR);

    $stmt->execute();

    return $this->conn->lastInsertId();

  }
  
  public function get(string $id): array | false
  {
      $sql = "SELECT *
              FROM tarefas
              WHERE id = :id";
              
      $stmt = $this->conn->prepare($sql);
      
      $stmt->bindValue(":id", $id, PDO::PARAM_INT);
      
      $stmt->execute();
      
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      
      return $data;
  }

  public function delete(string $id): int
  {
    $sql = "DELETE FROM tarefas WHERE id = :id";

    $stmt = $this->conn->prepare($sql);

    $stmt->bindValue(":id", $id, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->rowCount();
  }  
  
  public function update(array $current, array $new): int
  {
    $sql = "UPDATE tarefas SET titulo = :titulo, dever = :dever WHERE id = :id";

    $stmt = $this->conn->prepare($sql);

    $stmt->bindValue(":titulo", $new["titulo"] ?? $current["titulo"], PDO::PARAM_STR);

    $stmt->bindValue(":dever", $new["dever"] ?? $current["dever"], PDO::PARAM_STR);

    $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->rowCount();
  }
}

?>