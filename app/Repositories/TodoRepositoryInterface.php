<?php

namespace App\Repositories;

interface TodoRepositoryInterface
{
    public function allForUser($userId);
    public function create(array $data);
    public function findById($id, $userId);
    public function update($id, array $data, $userId);
    public function delete($id, $userId);
}
