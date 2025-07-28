<?php

namespace App\Core;

class App
{
  private Container $container;
  private Router $router;
  private static ?App $instance = null;

  private function __construct()
  {
    $this->container = Container::getInstance();
    $this->router = new Router($this->container);
    $this->loadRoutes();
  }

  public static function getInstance(): App
  {
    if (self::$instance === null) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  private function loadRoutes(): void
  {
    $routesPath = __DIR__ . '/../../routes/route.web.php';

    if (!file_exists($routesPath)) {
      return;
    }

    $routes = require $routesPath;

    if (!isset($routes['api'])) {
      return;
    }

    foreach ($routes['api'] as $path => $methods) {
      foreach ($methods as $method => $handler) {
        [$controller, $action] = $handler;
        $this->router->$method($path, $controller, $action);
      }
    }
  }

  public function run(): void
  {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];

    $this->router->dispatch($uri, $method);
  }

  public function getContainer(): Container
  {
    return $this->container;
  }

  public function getRouter(): Router
  {
    return $this->router;
  }
}
