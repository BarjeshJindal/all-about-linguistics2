@extends('layouts.vertical', ['title' => 'Add New Language', 'topbarTitle' => 'Add New Language'])

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">


            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close " data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            <div class="card shadow-sm border-0">
                <div class="card-body">


                    <form action="{{ route('admin.language.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-md-10 col-12">
                                <label class="form-label language-title">Language</label>
                                <input type="text" name="second_language" class="form-control" required
                                    placeholder="Type Language">


                            </div>

                            <div class="add-category d-flex align-items-center col-md-2 col-12 mt-2">
                                <button type="submit" class="btn btn-primary submit">
                                    <i class="bi bi-plus-circle me-1"></i> Add Language
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div>
                    <!-- @foreach ($languages as $language)
    {{ $language->second_language }}
    @endforeach -->

                </div>
            </div>
            <div class="card-body-table">
                <div class="card shadow-sm border-0">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Language</th>
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($languages as $language)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $language->second_language }}</td>
                                    <!-- <td><button onclick="removeRow(this)" class="btn btn-sm btn-danger">Remove</button></td> -->
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No languages available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <style>
        label.form-label.language-title {
            font-size: 20px;
        }

        .page-design-background.container.mt-4 {
            background: #ffffff;
            padding: 20px;
            border-radius: 9px;
            border: 1px solid #00000014;
            box-shadow: #64646f05 0px 7px 29px 0px;
            margin-bottom: 20px;
        }

        button.submit {
            background: #e1c21eed;
            border-color: #e1c21e;
        }

        button.submit:hover {
            background: #e1c21ecf;
            border-color: #e1c21e;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        thead {
            background-color: #e1c21e;
            color: white;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }

        button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #c82333;
        }
    </style>
@endsection
@section('scripts')
    @vite(['resources/js/pages/dashboard.js'])
@endsection
