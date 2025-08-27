@extends('layouts.vertical', ['title' => 'Dashboard', 'topbarTitle' => 'Dashboard'])

@section('content')
    <div>
        <form action="{{route('users.store')}}"method="post">
            @csrf
            {{-- @session('success')

                 <div class="alert alert-success">
            {{ $value }}
               </div>
          @endsession --}}

            <div class="mt-2">
                <label for="name">Name</label>
                <input type="text" name="name" id="">
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>


            <div class="mt-2">
                <label for="email">Email</label>
                <input type="text" name="email" id="">
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>


            <div class="mt-2">
                <label for="password">Password</label>
                <input type="text" name="password" id="">
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>





            <div class="form-group">
                <strong>Roles:</strong>
                <select name="role" class="form-control" >
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>

            </div>
            <button class="btn btn-success  mt-10"> Submit</button>

        </form>

    </div>
@endsection

@section('scripts')
    @vite(['resources/js/pages/dashboard.js'])
@endsection
