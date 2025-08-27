@extends('layouts.vertical', ['title' => 'Dashboard', 'topbarTitle' => 'Dashboard'])

@section('content')
<div class="card p-4">
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="create-role card-header bg-primary text-white rounded-top-4">
                <h4 class="mb-0">Create New Role</h4>
            </div>
            <div class="card-body p-4">

                <form method="POST" action="{{ route('admin.roles.store') }}">
                    @csrf

                    <!-- Role Name -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold" for="role">Role Name</label>
                        <input type="text" id="role" name="name" 
                               class="form-control form-control-lg @error('name') is-invalid @enderror"
                               placeholder="Enter role name (e.g. Instructor)">
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Permissions -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Assign Permissions</h5>
                        
                        <div class="row g-3">
                            @foreach ($permissions as $permission)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="permissions[{{ $permission->name }}]" 
                                               value="{{ $permission->name }}" 
                                               id="perm_{{ $permission->id }}">
                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                            {{ ucfirst(str_replace('_', ' ', $permission->name)) }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="d-grid">
                        <button class="btn creat-role-btn btn-primary btn-lg fw-semibold" type="submit">
                            <i class="bi bi-plus-circle me-2"></i> Create Role
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('scripts')
@vite(['resources/js/pages/dashboard.js'])
@endsection
