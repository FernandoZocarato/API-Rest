<?php

class TituloController
{
  public function __construct(private TituloGateway $gateway) 
  {

  }

  public function processRequest(string $method, ?string $id): void
  {
    if ($id) {
      $this->processResourceRequest($method, $id);
    } else {
      $this->processCollectionRequest($method);
    }
  }

  private function processResourceRequest(string $method, string $id): void
  {
    $titulo = $this->gateway->get($id);

    if (! $titulo) {
      http_response_code(404);
      echo json_encode(["message" => "Título não encontrado!"]);
      return;
    }

    switch ($method) {
      case 'GET':
        echo json_encode($titulo);
        break;

      case 'PUT':
        $data = (array) json_decode(file_get_contents("php://input"), true);
        
        $rows = $this->gateway->update($titulo, $data);

        echo json_encode([
          "message" => "Tarefa $id atualizada",
          "rows" => $rows
        ]);
        break;

      case 'DELETE':
        $rows = $this->gateway->delete($id);

        echo json_encode ([
          "message" => "titulo $id deletado",
          "rows" => $rows
        ]);
        break;

      default: 
      http_response_code(405);
      header("Allow: GET, POST, DELETE");
    }
  }

  private function processCollectionRequest(string $method): void
  {
    switch ($method) {
      case 'GET':
        echo json_encode($this->gateway->getAll());
        break;

      case 'POST':
        $data = (array) json_decode(file_get_contents("php://input"), true);
        
        $errors = $this->getValidationErrors($data);

        if (! empty($errors)) {
          http_response_code(422);
          echo json_encode(["errors" => $errors]);
          break;
        }
        
        $id = $this->gateway->create($data);

        http_response_code(201);
        echo json_encode([
          "message" => "Tarefa criada",
          "id" => $id
        ]);
        break;

        default: 
        http_response_code(405);
        header("Allow: GET, POST");
    }
  }

  private function getValidationErrors(array $data): array
  {
    $errors = [];

    if (empty($data["titulo"])) {
      $errors[] = "Título é necessário!";
    }

    if (empty($data["dever"])) {
      $errors[] = "Dever é necessário!";
    }

    return $errors;

  }
}

?>