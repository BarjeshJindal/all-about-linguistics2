@extends('layouts.vertical', ['title' => 'Ticket', 'topbarTitle' => 'Ticket'])

@section('content')
    <div class="card p-4">
        <div class="row justify-content-center">
            <div class="container create-ticket-box my-5 fund-request-form">
                <h3 class="creagte-ticket-title mb-3">Create a New Ticket</h3>

                {{-- ✅ Success Message --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('user.ticket.create') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">Ticket Title</label>
                        <input type="text" name="title" id="title" class="form-control" required
                               placeholder="Enter ticket subject">
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Describe Your Issue</label>
                        <textarea name="message" id="message" rows="5" class="form-control" required
                                  placeholder="Write your message here..."></textarea>
                    </div>

                    <button type="submit" class="btn submit-ticket">Submit Ticket</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/pages/dashboard.js'])
@endsection
