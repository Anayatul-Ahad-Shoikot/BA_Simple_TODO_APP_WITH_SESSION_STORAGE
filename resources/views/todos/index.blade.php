<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
    <link href="{{ asset('css/todoDash.css') }}" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .finished {
            background-color: lightgreen;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <a class="navbar-brand" href="#">Todo App</a>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#">{{ auth()->user()->name }}</a>
                </li>
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">My Todo List</h1>
        <form id="todo_create" action="{{ route('todos.store') }}" method="POST" class="mb-3">
            @csrf
            <div class="input-group">
                <input type="text" name="todo" class="form-control" placeholder="New Todo" required>
                <button type="submit" class="btn btn-primary">Add Todo</button>
            </div>
        </form>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <ul class="list-group">
            @forelse($todos as $index => $todo)
                <li
                    class="list-group-item d-flex justify-content-between align-items-center {{ $todo['status'] == 'finished' ? 'finished' : '' }}">
                    <div class="d-flex align-items-center">
                        <input type="checkbox" class="form-check-input me-2"
                            onchange="toggleStatus({{ $index }})"
                            {{ $todo['status'] == 'finished' ? 'checked' : '' }}>
                        {{ $todo['name'] }}
                    </div>
                    <div class="btn-group">
                        <form action="{{ route('todos.update', $index) }}" method="POST" class="me-2">
                            @csrf
                            @method('PUT')
                            <input type="text" name="todo" value="{{ $todo['name'] }}" required
                                class="form-control me-2">
                            <button type="submit" class="btn btn-warning">Update</button>
                        </form>
                        <form action="{{ route('todos.destroy', $index) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </li>
            @empty
                <li class="list-group-item">No todos available.</li>
            @endforelse
        </ul>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>


    <script>
        function toggleStatus(index) {
            fetch(`/todos/${index}/toggle-status`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                });
        }
    </script>
</body>

</html>
