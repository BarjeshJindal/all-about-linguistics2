@extends('layouts.vertical', ['title' => 'Dashboard', 'topbarTitle' => 'Dashboard'])

@section('content')
    <div class="card p-4">
        <div class="row justify-content-center">
            <div class="container my-5 fund-request-form">

                <div class="chat-box">
                    {{-- ✅ Success Message --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="ticket-details-card">
                        <h4 class="ticket-heading">🎫 Ticket Details</h4>
                        <div class="ticket-info">
                            <p><strong>Name :</strong> <span>{{ $user->name }}</span></p>
                            <p><strong>Email :</strong> <span>{{ $user->email }}</span></p>
                            <p><strong>Title :</strong> <span>{{ $ticket->title }}</span></p>
                            <p>
                                <strong>Status :</strong>
                                <span class="status {{ $ticket->status == 'open' ? 'open' : 'text-danger' }}">
                                    {{ strtoupper($ticket->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <h5 class="mb-4">Chat Messages</h5>

                    @foreach ($ticket->messages as $msg)
                        <div class="message {{ $msg->sender_type }}">
                            <div>
                                <div class="sender {{ $msg->sender_type == 'user' ? 'text-end' : '' }}">
                                    {{ $msg->sender_type == 'user' ? 'User' : 'Admin' }}
                                </div>
                                <div class="bubble">{{ $msg->message }}</div>
                                <div class="time {{ $msg->sender_type == 'user' ? 'text-end' : '' }}">
                                    {{ $msg->created_at->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if ($ticket->status === 'open')
                        <form method="POST" action="{{ route('ticket.message.send', $ticket->id) }}" class="chat-footer d-flex">
                            @csrf
                            <input type="text" name="message" class="form-control me-2"
                                   placeholder="Type your message..." required>
                            <button class="btn btn-primary">Send</button>
                        </form>
                    @else
                        <div class="alert alert-info mt-4">This ticket is closed.</div>
                        <form method="POST" action="{{ route('ticket.reopen', $ticket->id) }}" class="mt-2 text-end">
                            @csrf
                            <button type="submit" class="btn btn-success">Reopen Ticket</button>
                        </form>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/pages/dashboard.js'])
@endsection
