<?php

namespace App\Core;

class Router
{
  private array $routes = [];
  private Container $container;

  public function __construct(Container $container)
  {
    $this->container = $container;
  }

  public function get(string $path, string $controller, string $method): void
  {
    $this->addRoute('GET', $path, $controller, $method);
  }

  public function post(string $path, string $controller, string $method): void
  {
    $this->addRoute('POST', $path, $controller, $method);
  }

  public function put(string $path, string $controller, string $method): void
  {
    $this->addRoute('PUT', $path, $controller, $method);
  }

  public function delete(string $path, string $controller, string $method): void
  {
    $this->addRoute('DELETE', $path, $controller, $method);
  }

  private function addRoute(string $method, string $path, string $controller, string $action): void
  {
    $this->routes[] = [
      'method' => $method,
      'path' => $path,
      'controller' => $controller,
      'action' => $action
    ];
  }

  public function dispatch(string $uri, string $method): void
  {
    $uri = trim($uri, '/');

    foreach ($this->routes as $route) {
      if ($route['method'] !== $method) {
        continue;
      }

      $pattern = $this->buildPattern($route['path']);
      if (preg_match($pattern, $uri, $matches)) {
        $this->executeRoute($route, $matches);
        return;
      }
    }

    // Route non trouvée
    $this->notFound();
  }

  private function buildPattern(string $path): string
  {
    // Convertir les paramètres {id} en groupes de capture
    $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $path);
    return '#^' . $pattern . '$#';
  }

  private function executeRoute(array $route, array $matches): void
  {
    try {
      $controller = $this->container->get($route['controller']);
      $action = $route['action'];

      // Extraire les paramètres de l'URL
      $params = array_slice($matches, 1);

      if (method_exists($controller, $action)) {
        call_user_func_array([$controller, $action], $params);
      } else {
        throw new \Exception("Method '$action' not found in controller");
      }
    } catch (\Exception $e) {
      $this->error($e->getMessage());
    }
  }

  private function notFound(): void
  {
    header('Content-Type: application/json');
    http_response_code(404);
    echo json_encode([
      'data' => null,
      'statut' => 'error',
      'code' => 404,
      'message' => 'Route non trouvée'
    ]);
  }

  private function error(string $message): void
  {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
      'data' => null,
      'statut' => 'error',
      'code' => 500,
      'message' => $message
    ]);
  }
}
