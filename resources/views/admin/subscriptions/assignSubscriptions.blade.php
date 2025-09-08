@extends('layouts.vertical', ['title' => 'Manage Practice Dialogues', 'topbarTitle' => 'Manage Practice Dialogues'])

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <p style="color: red;">{{ session('error') }}</p>
            @endif

            <div class="d-flex justify-content-between mb-3">
                <h2>Assign Subscription to User: {{ $user->name }} ({{ $user->email }})</h2>
            </div>

            <form method="POST" action="{{ route('admin.users.assign-subscription', $user->id) }}"
                enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="subscription_plan_id">Select Subscription Plan:</label>
                    <select id="subscription_plan_id" name="subscription_plan_id" class="form-control" required>
                        <option value="">Select Plan</option>
                        @foreach ($plans as $plan)
                            <option value="{{ $plan->id }}">
                                {{ $plan->plan_type }} ({{ $plan->duration_days }} Days)
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-success submit">Assign Plan</button>
            </form>

        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
