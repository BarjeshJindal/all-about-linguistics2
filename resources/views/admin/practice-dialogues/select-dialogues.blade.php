@extends('layouts.vertical', ['title' => 'Select Practice Dialogues', 'topbarTitle' => 'Select Practice Dialogues'])



@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="d-flex justify-content-between mb-3">
            {{-- <h4>Assign Practice Dialogues to {{ $plan->name }}</h4> --}}
        </div>

        @if($practiceDialogues->isEmpty())
            <div class="text-center py-4">
                <p>No Practice Dialogues available.</p>
            </div>
        @else
            <form method="POST" action="{{ route('admin.update-selected-dialogue')}}">
                @csrf

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="50">Select</th>
                            <th>#</th>
                            <th>Dialogue</th>
                            <th>Description</th>
                            {{-- <th>Date</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($practiceDialogues as $practiceDialogue)
                            {{-- @php
                                $assigned = DB::table('naati_plan_dialogue')
                                    ->where('plan_id', $plan->id)
                                    ->where('dialogue_id', $practiceDialogue->id)
                                    ->exists();
                            @endphp --}}
                            <tr>
                                <td>
                                    <input type="checkbox" name="dialogues[]" value="{{ $practiceDialogue->id }}"
                                     {{ in_array($practiceDialogue->id, $assigned) ? 'checked' : '' }}>
                                </td>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ Str::limit($practiceDialogue->title, 80) }}</td>
                                 <td>{{ Str::limit($practiceDialogue->description, 80) }}</td>
                                {{-- <td>{{ $practiceDialogue->created_at->format('d M Y') }}</td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                 {{-- <input type="hidden" name="pla"> --}}
                <button type="submit" class="choose-btn mt-3">Save Dialogues</button>
            </form>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection