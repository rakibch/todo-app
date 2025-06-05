<?php

namespace App\Repositories;

use App\Models\Todo;

class TodoRepository implements TodoRepositoryInterface
{
    public function allForUser($userId)
    {
        return Todo::where('user_id', $userId)->get();
    }

    public function create(array $data)
    {
        return Todo::create($data);
    }

    public function findById($id, $userId)
    {
        return Todo::where('id', $id)->where('user_id', $userId)->first();
    }

    public function update($id, array $data, $userId)
    {
        $todo = $this->findById($id, $userId);
        if ($todo) {
            $todo->update($data);
        }
        return $todo;
    }

    public function delete($id, $userId)
    {
        $todo = $this->findById($id, $userId);
        if ($todo) {
            return $todo->delete();
        }
        return false;
    }
}
