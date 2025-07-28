<?php

namespace App\Core;

use Symfony\Component\Yaml\Yaml;

class Container
{
  private array $services = [];
  private array $dependencies = [];
  private static ?Container $instance = null;

  private function __construct()
  {
    $this->loadDependencies();
  }

  public static function getInstance(): Container
  {
    if (self::$instance === null) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  private function loadDependencies(): void
  {
    $yamlContent = file_get_contents(__DIR__ . '/../config/dependencies.yaml');
    $this->dependencies = Yaml::parse($yamlContent);
  }

  public function get(string $serviceName): object
  {
    if (isset($this->services[$serviceName])) {
      return $this->services[$serviceName];
    }

    $service = $this->createService($serviceName);
    $this->services[$serviceName] = $service;
    return $service;
  }

  private function createService(string $serviceName): object
  {
    // Chercher dans toutes les catégories
    foreach ($this->dependencies['dependencies'] as $category => $services) {
      if (isset($services[$serviceName])) {
        $className = $services[$serviceName];
        return $this->instantiateClass($className, $serviceName);
      }
    }

    throw new \Exception("Service '$serviceName' not found in dependencies");
  }

  private function instantiateClass(string $className, string $serviceName): object
  {
    $reflection = new \ReflectionClass($className);
    $constructor = $reflection->getConstructor();

    if ($constructor === null) {
      return new $className();
    }

    $parameters = $constructor->getParameters();
    $arguments = [];

    foreach ($parameters as $parameter) {
      $type = $parameter->getType();

      if ($type === null) {
        throw new \Exception("Parameter '{$parameter->getName()}' in '$className' has no type hint");
      }

      $typeName = $type->getName();

      if ($typeName === 'PDO') {
        $arguments[] = $this->getPDO();
      } elseif ($typeName === 'App\Core\Container') {
        // Injection du conteneur lui-même
        $arguments[] = $this;
      } else {
        // Chercher le service correspondant
        $serviceKey = $this->findServiceByClass($typeName);
        if ($serviceKey) {
          $arguments[] = $this->get($serviceKey);
        } else {
          throw new \Exception("Cannot resolve dependency '$typeName' for service '$serviceName'");
        }
      }
    }

    return new $className(...$arguments);
  }

  private function findServiceByClass(string $className): ?string
  {
    // Vérifier d'abord les mappings d'interfaces
    if (isset($this->dependencies['dependencies']['interface_mappings'][$className])) {
      $implementationClass = $this->dependencies['dependencies']['interface_mappings'][$className];
      // Chercher le service correspondant à l'implémentation
      foreach ($this->dependencies['dependencies'] as $category => $services) {
        if ($category === 'interface_mappings') continue;
        foreach ($services as $serviceName => $serviceClass) {
          if ($serviceClass === $implementationClass) {
            return $serviceName;
          }
        }
      }
    }

    // Chercher dans toutes les catégories
    foreach ($this->dependencies['dependencies'] as $category => $services) {
      if ($category === 'interface_mappings') continue;
      foreach ($services as $serviceName => $serviceClass) {
        if ($serviceClass === $className) {
          return $serviceName;
        }
      }
    }
    return null;
  }

  private function getPDO(): \PDO
  {
    if (!isset($this->services['PDO'])) {
      $config = require __DIR__ . '/../../config/database.php';
      $this->services['PDO'] = new \PDO(
        $config['dsn'],
        $config['username'],
        $config['password'],
        $config['options']
      );
    }
    return $this->services['PDO'];
  }

  public function has(string $serviceName): bool
  {
    foreach ($this->dependencies['dependencies'] as $category => $services) {
      if (isset($services[$serviceName])) {
        return true;
      }
    }
    return false;
  }
}
