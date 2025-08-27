@extends('layouts.vertical', ['title' => 'Mock Tests', 'topbarTitle' => 'Mock Tests'])

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">All Mock Tests</h4>
        <a href="{{ route('admin.mock-tests.create') }}" class="btn btn-primary">+ Add New Mock Test</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($mockTests->isEmpty())
        <div class="alert alert-info">No mock tests found.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Second Language</th>
                        <th>Dialogues</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mockTests as $index => $mockTest)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $mockTest->title }}</td>
                            <td>{{ ucfirst($mockTest->second_language) }}</td>
                            <td>{{ $mockTest->mockDialogues->count() }} dialogues</td>
                            <td>{{ $mockTest->created_at->format('d M, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.mock-tests.show', $mockTest->id) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('admin.mock-tests.edit', $mockTest->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.mock-tests.destroy', $mockTest->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure to delete this mock test?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
