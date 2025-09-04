@extends('layouts.vertical', ['title' => 'Manage Vip Exam ', 'topbarTitle' => 'Manage Vip Exam'])

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-between mb-3">
            <h4>All Vip Exam</h4>
            {{-- <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">+ Add FAQ</a> --}}
        </div>

        @if($practiceDialogues->isEmpty())
            <div class="text-center py-4">
                <p>No Vip Exam available.</p>
            </div>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Dialogue</th>
                        <th>Date</th>
                        {{-- <td></td> --}}
                        <th width="150">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($practiceDialogues as $practiceDialogue)
                        <tr>
                            <td>{{$loop->iteration }}</td>
                            <td>{{ Str::limit($practiceDialogue->title, 80) }}</td>
                            <td>{{ $practiceDialogue->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.vip-exams.edit', $practiceDialogue->id) }}" class="btn btn-sm btn-warning">Edit</a>

                                {{-- <form id="delete-form-{{ $faq->id }}" action="{{ route('admin.faqs.delete', $faq->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete({{ $faq->id }})" class="btn btn-sm btn-danger">Delete</button>
                                </form> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This FAQ will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endsection