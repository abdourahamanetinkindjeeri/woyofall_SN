<?php

namespace App\Repository\IRepository;

use App\Entity\Client;

interface ClientRepositoryInterface
{
  public function insert(Client $entity): Client;
  public function update(Client $entity, array $filter): Client;
  public function findOneBy(array $criteria): ?Client;
}
