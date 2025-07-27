<?php

namespace App\Service\IService;

use App\Entity\Client;

interface ClientServiceInterface
{
  public function create(array $data): Client;
  public function update(array $data, array $filter): Client;
  public function recupererClient(int $id): ?Client;
}
