@extends('layouts.vertical', ['title' => 'Dashboard', 'topbarTitle' => 'Dashboard'])

@section('content')
    <div class="card p-4">
        <div class="row justify-content-center">
            <div class="row">

                <h2 class="form-title">All Support Tickets</h2>

                <div class="table-responsive">
                    <table id="data_table"
                           class="table table-bordered table-striped table-hover align-middle text-nowrap">
                        <thead class="table-head" style="background-color: #dc3545; color: white;">
                            <tr>
                                <th>Sr.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($tickets->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center">No tickets found.</td>
                                </tr>
                            @else
                                @foreach ($tickets as $index => $ticket)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $ticket->user_name }}</td>
                                        <td>{{ $ticket->user_email }}</td>
                                        <td>{{ $ticket->title }}</td>
                                        <td>{{ strtoupper($ticket->status) }}</td>
                                        <td>{{ $ticket->created_at->format('d-m-Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.ticket.view', $ticket->id) }}"
                                               class="btn btn-primary ticket-view-btn btn-sm">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/pages/dashboard.js'])
@endsection
