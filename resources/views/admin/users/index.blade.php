@extends('layouts.vertical', ['title' => 'Dashboard', 'topbarTitle' => 'Dashboard'])

@section('content')
    <table>



        @can('role-edit')
            <a href="{{ route('users.create') }}" class="btn btn-success">Create User</a>
        @endcan
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>

                <th>ROLE</th>
                <th> EDIT</th>
                <th> DELETE</th>
                {{-- @can('role-delete')
                        <th> DELETE</th>ss
                    @endcan --}}

            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    {{-- <td> {{ $user }}</td> --}}


                    <td>
                        @foreach ($user->getRoleNames() as $role)
                            <button class="btn btn-success"> {{ $role }}</button>
                        @endforeach
                    </td>
                    @can('user-edit')
                        <td>
                            <a href="" class="btn btn-success">EDIT</a>
                        </td>
                    @endcan

                    @can('user-delete')
                        <td>
                            <form action="{{ route('users.destroy', $user->id) }}" method="post">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-success">DELETE</button>
                            </form>
                        </td>
                    @endcan
                    <td>

                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
@endsection

@section('scripts')
    @vite(['resources/js/pages/dashboard.js'])
@endsection
