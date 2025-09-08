@extends('layouts.vertical', ['title' => 'Manage Subscriptions', 'topbarTitle' => 'Manage Subscriptions'])



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

        @if($plans->isEmpty())
            <div class="text-center py-4">
                <p>No Plans available.</p>
            </div>
        @else
            {{-- <form method="POST" action="{{ route('admin.update-selected-dialogue')}}">
                @csrf --}}

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="50">Sr. No</th>
                           
                            <th>Plan</th>
                            <th>Action</th>
                         
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plans as $plan)
                          
                            <tr>
                                
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ strtoupper($plan->plan_type) }}</td>
                                <td >
                                 <a  class="choose-btn choose" href="{{ route('admin.select-practice-dialogue',$plan->id) }}">Edit</a>    
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

@endsection