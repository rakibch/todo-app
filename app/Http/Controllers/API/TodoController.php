<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\TodoRepositoryInterface;

class TodoController extends Controller
{
    protected $todoRepo;

    public function __construct(TodoRepositoryInterface $todoRepo)
    {
        $this->todoRepo = $todoRepo;
    }
    public function index(Request $request)
    {
        $todos = $this->todoRepo->allForUser($request->user()->id);
        return response()->json($todos);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
        ]);

        $validated['user_id'] = $request->user()->id;

        $todo = $this->todoRepo->create($validated);
        return response()->json($todo, 201);
    }
    public function show($id, Request $request)
    {
        $todo = $this->todoRepo->findById($id, $request->user()->id);
        if (!$todo) {
            return response()->json(['message' => 'Todo not found'], 404);
        }
        return response()->json($todo);
    }
     public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $todo = $this->todoRepo->update($id, $validated, $request->user()->id);
        if (!$todo) {
            return response()->json(['message' => 'Todo not found or unauthorized'], 404);
        }

        return response()->json($todo);
    }

    public function destroy($id, Request $request)
    {
        $deleted = $this->todoRepo->delete($id, $request->user()->id);
        if (!$deleted) {
            return response()->json(['message' => 'Todo not found or unauthorized'], 404);
        }

        return response()->json(['message' => 'Todo deleted successfully']);
    }
}
