@extends('layouts.vertical', ['title' => 'Ticket', 'topbarTitle' => 'Ticket'])

@section('content')
    <div class="card p-4">
        <div class="row justify-content-center">  
            <div class="container m-0 py-4 fund-request-form">
                <div class="row">
                    <h2 class="form-title">Open Tickets</h2>

                    <div class="table-responsive">
                        <table id="data_table"
                               class="table table-bordered table-striped table-hover align-middle text-nowrap">
                            <thead class="table-head" style="background-color: #ebe716; color: white;">
                                <tr>
                                    <th scope="col">Sr. No</th>
                                    <th>Ticket Title</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($tickets->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center">No tickets found.</td>
                                    </tr>
                                @else
                                    @foreach ($tickets as $index => $ticket)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $ticket->title }}</td>
                                            <td>{{ ucfirst($ticket->status) }}</td>
                                            <td>{{ $ticket->created_at->format('d-m-Y') }}</td>
                                            <td>
                                                <a href="{{ route('ticket.details', $ticket->id) }}"
                                                   class="btn btn-success btn-sm">
                                                    Reply
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
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/pages/dashboard.js'])
@endsection
