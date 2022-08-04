<?php
declare(strict_types=1);

spl_autoload_register(function ($class) {
  require __DIR__ . "/src/$class.php";
});

set_error_handler("ErrorHandler::handleError");

set_exception_handler("ErrorHandler::handleException");

header("HTTP/1.1 200 OK");

header("Content-type: application/json; charset=UTF-8");

header("Access-Control-Allow-Origin: *");

// header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Methods: *");

header("Access-Control-Allow-Headers: origin, X-Auth-Token, content-type, Authorization");

$parts = explode("/", $_SERVER['REQUEST_URI']);

if ($parts[1] != 'tarefas') {
  http_response_code(404);
  exit;
}

$id = $parts[2] ?? null;

$database = new Database("localhost", "lista_tarefas", "root", "");

$gateway = new TituloGateway($database);

$controller = new TituloController($gateway);

$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);

?>