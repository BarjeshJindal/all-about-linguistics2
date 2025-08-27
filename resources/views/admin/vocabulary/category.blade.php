@extends('layouts.vertical', ['title' => 'Vocabulary', 'topbarTitle' => 'Vocabulary'])

@section('content')

    {{-- add category --}}
    <div class="page-design-background container mt-4">

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
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('admin.category.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-10 col-12">
                            <label class="form-label language-title">Category</label>
                            <input type="text" name="name" class="form-control" required placeholder="Type Category">

                        </div>
                        <div class="add-category d-flex align-items-center col-md-2 col-12 mt-2">
                            <button type="submit" class="btn btn-primary submit">
                                <i class="bi bi-plus-circle me-1"></i> Add Category
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div> --}}

            <div class="card shadow-sm border-0" style="overflow-x: scroll;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Category</th>
                            <th>Words</th>
                        </tr>

                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <a href="{{ route('admin.vocabulary.words-list', $category->id) }}">
                                        {{ $category->name }}</a>
                                </td>
                                <td>{{ $category->words_count }}</td>
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

    <style>
        th {
            white-space: nowrap;
        }

        button.btn.btn-primary.submit {
            padding: 10px;
            height: fit-content;
            width: auto;
        }

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
