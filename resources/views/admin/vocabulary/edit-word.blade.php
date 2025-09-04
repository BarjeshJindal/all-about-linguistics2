@extends('layouts.vertical', ['title' => 'Vocabulary', 'topbarTitle' => 'Edit Word'])

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="col-xl-6 mx-auto">
                <div class="card shadow-lg rounded-2xl p-4">
                    <h4 class="mb-3">Update Word</h4>

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.vocabulary.words-update',$word->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Word</label>
                            <input type="text" name="word" class="form-control" value="{{ $word->word }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Meaning</label>
                            <input type="text" name="meaning" class="form-control" value="{{ $word->meaning }}" required>
                        </div>

                        {{-- <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ $user->phone }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="language" class="form-label">Your CCL Test Language*</label>
                            <select class="form-select" id="language" disabled name="language"
                                style="background-color: white; color: black;">

                                <option value="">{{ $language }}</option>
                            </select>
                        </div> --}}
                        {{-- <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" value="{{ $user->password }}" required>
            </div> --}}

                        {{-- <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div> --}}
                        {{-- <div class="mb-3">
                    <label class="form-label language-title">Role</label>
                    <select name="role" class="form-control" required>
                        <option value="">-- Select Role --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
            </div> --}}

                        <button type="submit" class="btn add-user-btn text-md btn-primary w-100">Update Word</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
