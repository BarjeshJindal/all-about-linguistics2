@extends('layouts.vertical', ['title' => 'Dashboard', 'topbarTitle' => 'Dashboard'])

@section('content')
    <div>
        <form action="{{ route('admin.segments.store', $practice) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Answer(English)</label>
                <input type="text" name="answer_eng" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Answer Second Language</label>
                <input type="text" name="answer_second_language" class="form-control" required>
            </div>
            <input type="hidden" name="segment_parent_id" value="{{ $practice->id }}">
            <input type="hidden" name="segment_type" value='1'>

            <div class="mb-4 border p-3 rounded bg-light">
                <label class="form-label">MP3 File</label>
                <input type="file" name="segment_path" class="form-control" accept=".mp3,.wav" required>
            </div>

            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>
@endsection
@section('scripts')
    @vite(['resources/js/pages/dashboard.js'])
@endsection
