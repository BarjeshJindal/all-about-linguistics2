@extends('layouts.vertical', ['title' => 'Words', 'topbarTitle' => 'Words'])

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">

            {{-- Flash Messages --}}
            @if (session('flash_message'))
                <div class="alert alert-{{ session('flash_type') }} alert-dismissible fade show" role="alert">
                    {!! session('flash_message') !!}
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



            <div class="d-flex flex-wrap gap-3 justify-content-around">
                {{-- ===== Form 1: Add Single Word ===== --}}
                <div class="card shadow-sm border-0 flex-fill" style="min-width: 350px; max-width: 48%;">
                    <div class="card-body">
                        <h3 class="mb-3">Add Individual Word</h3>
                        <form action="{{ route('admin.single-word.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label language-title">Category</label>
                                <select name="category_id" class="form-control" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label language-title">Language</label>
                                <select name="language_id" class="form-control" required>
                                    <option value="">-- Select Language --</option>
                                    @foreach ($languages as $language)
                                        <option value="{{ $language->id }}">{{ $language->second_language }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label language-title">Word</label>
                                <input type="text" name="word" class="form-control" required placeholder="Type Word">
                            </div>

                            <div class="mb-3">
                                <label class="form-label language-title">Meaning</label>
                                <input type="text" name="meaning" class="form-control" required
                                    placeholder="Type Meaning">
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary submit">
                                    <i class="bi bi-plus-circle me-1"></i> Add Word
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ===== Form 2: Upload CSV ===== --}}
                <div class="card shadow-sm border-0 flex-fill" style="min-width: 350px; max-width: 48%;">
                    <div class="card-body">
                        <h3 class="mb-3">Import Words via File</h3>
                        <form action="{{ route('admin.word.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label language-title">Category</label>
                                <select name="category_id" class="form-control" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label language-title">Language</label>
                                <select name="language_id" class="form-control" required>
                                    <option value="">-- Select Language --</option>
                                    @foreach ($languages as $language)
                                        <option value="{{ $language->id }}">{{ $language->second_language }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Choose xlsx File</label>
                                <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('admin.download.sample-words') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-download me-1"></i> Download Sample File
                                </a>

                                <button type="submit" class="btn btn-primary submit">
                                    <i class="bi bi-upload me-1"></i> Upload File
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>


        </div>

    </div>

    <style>
        h3.mb-3 {
            background: #e3c62e;
            padding: 7px;
            border-radius: 4px;
            font-size: 20px;
            text-align: center;
            color: #fff;
        }

        .card-body {
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            border-radius: 5px;
        }

        label.form-label.language-title {
            font-size: 16px;
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

        /* Responsive */
        @media (max-width: 992px) {
            .d-flex.flex-wrap .card {
                max-width: 100% !important;
            }
        }
    </style>
@endsection

@section('scripts')
    @vite(['resources/js/pages/dashboard.js'])
@endsection
