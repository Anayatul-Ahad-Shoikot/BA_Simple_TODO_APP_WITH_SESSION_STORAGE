<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        $todos = $request->session()->get('todos', []);
        return view('todos.index', compact('todos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'todo' => 'required|string|max:255',
        ]);

        $todos = $request->session()->get('todos', []);
        $todos[] = [
            'name' => $request->input('todo'),
            'status' => 'running' 
        ];

        $request->session()->put('todos', $todos);

        return redirect()->route('todos.index');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'todo' => 'required|string|max:255',
        ]);
        $todos = $request->session()->get('todos', []);
        if (isset($todos[$id])) {
            $todos[$id]['name'] = $request->input('todo');
            $request->session()->put('todos', $todos);
        }

        return redirect()->route('todos.index');
    }

    public function toggleStatus(Request $request, $id)
    {
        $todos = $request->session()->get('todos', []);
        if (isset($todos[$id])) {
            $todos[$id]['status'] = $todos[$id]['status'] === 'finished' ? 'running' : 'finished';
            $request->session()->put('todos', $todos);
        }

        return response()->json(['success' => true]);
    }

    public function destroy(Request $request, $id)
    {
        $todos = $request->session()->get('todos', []);
        if (isset($todos[$id])) {
            unset($todos[$id]);
            $request->session()->put('todos', array_values($todos));
        }

        return redirect()->route('todos.index');
    }
}
