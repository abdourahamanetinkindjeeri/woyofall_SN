<?php

namespace App\Service;

use App\Entity\Client;
use App\Repository\IRepository\ClientRepositoryInterface;
use App\Service\IService\ClientServiceInterface;

class ClientService implements ClientServiceInterface
{
  private ClientRepositoryInterface $repository;

  public function __construct(ClientRepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  public function create(array $data): Client
  {
    return $this->repository->insert(Client::toObject($data));
  }

  public function update(array $data, array $filter): Client
  {
    return $this->repository->update(Client::toObject($data), $filter);
  }

  public function recupererClient(int $id): ?Client
  {
    return $this->repository->findOneBy(['id' => $id]);
  }

  public function getAllClients(): array
  {
    return $this->repository->selectAll();
  }
}
