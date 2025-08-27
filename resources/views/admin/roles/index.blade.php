@extends('layouts.vertical', ['title' => 'Dashboard', 'topbarTitle' => 'Dashboard'])

@section('content')
<div class="card p-md-4">
    <div class="container">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="mb-3">
            @can('role-edit')
                <a href="{{ route('roles.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Create Role
                </a>
            @endcan
        </div>

        <table id="datatable-buttons"
                                            class="table table-striped dt-responsive nowrap w-100">
            <thead class="table-light">
                <tr>
                    <th>Sr. No</th>
                    <th>Name</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ ucfirst($role->name) }}</td>
                        <td>
                            @if ($role->name !== 'admin')
                                <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                            @else
                                <span class="btn btn-sm btn-danger">Locked</span>
                            @endif
                        </td>
                        <td>
                            @if ($role->name !== 'admin')
                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="post" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            @else
                                <span class="btn btn-sm btn-danger">Locked</span>
                            @endif    
                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
       </div>
    <style>
         table,
        th,
        td {
            border: 1px solid #dee2e6;
            border-collapse: collapse;
            text-align: center;
            white-space: nowrap;
        }

        th {
            background: #e1c426 !important;
            color: #ffff !important;
        }

        .nav-tabs.nav-bordered-danger .nav-item .nav-link.active {
            border-bottom: 1px solid #000000;
            color: #000000;
        }

        @media (max-width: 768px) {
            .tab-content {
                overflow-x: auto;
            }
        }
    </style>
@endsection

@section('scripts')
    @vite(['resources/js/pages/dashboard.js'])
@endsection
