@extends('layouts.vertical', ['title' => 'Dashboard', 'topbarTitle' => 'Dashboard'])

@section('content')
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header border-bottom border-dashed d-flex align-items-center">
                <h4 class="header-title">Question List</h4>
            </div>

            <div class="card-body">

                <ul class="nav nav-tabs nav-justified nav-bordered nav-bordered-danger mb-3">
                    <li class="nav-item">
                        <a href="#home-b2" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                            <i class="ti ti-home fs-18 me-md-1"></i>
                            <span class="d-none d-md-inline-block">Practiceedgdfwef</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#profile-b2" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            <i class="ti ti-user-circle fs-18 me-md-1"></i>
                            <span class="d-none d-md-inline-block">Pending Practice</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#settings-b2" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            <i class="ti ti-settings fs-18 me-md-1"></i>
                            <span class="d-none d-md-inline-block">Completed Practice</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    {{-- Practice Tab --}}
                    <div class="tab-pane show active" id="home-b2">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h5 class="check-label">
                                            Label 
                                            <i class="ri-price-tag-3-fill first"></i> 
                                            <i class="ri-price-tag-3-fill second"></i> 
                                            <i class="ri-price-tag-3-fill third"></i> 
                                            <i class="ri-price-tag-3-fill fourth"></i>
                                        </h5>

                                        <a href="{{ route('admin.practices.create') }}" class="btn btn-danger btn-sm mb-3">
                                            ADD PRACTICE TEST
                                        </a>

                                        <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    {{-- <th>View</th> --}}
                                                    <th>Tag</th>
                                                    <th>Score</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($practices as $practice)
                                                    <tr>
                                                        <td>{{ $practice->title }}</td>
                                                        {{-- <td class="click-view">
                                                            <a href="{{ route('admin.segments.index', $practice->id) }}">
                                                                Click to view
                                                            </a>
                                                        </td> --}}
                                                        <td><i class="ri-price-tag-3-fill"></i></td>
                                                        <td>#61</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
                        </div> <!-- end row-->
                    </div>

                    {{-- Pending Practice Tab --}}
                    <div class="tab-pane" id="profile-b2">
                        @if(isset($pendingPractices) && $pendingPractices->count())
                            @foreach ($pendingPractices as $practice)
                                <div class="mb-2">
                                    <strong>{{ $practice->title }}</strong>
                                    <p>Status: Pending Review</p>
                                </div>
                            @endforeach
                        @else
                            <p>No pending practice available.</p>
                        @endif
                    </div>

                    {{-- Completed Practice Tab --}}
                    <div class="tab-pane" id="settings-b2">
                        @if(isset($completedPractices) && $completedPractices->count())
                            @foreach ($completedPractices as $practice)
                                <div class="mb-2">
                                    <strong>{{ $practice->title }}</strong>
                                    <p>Status: Completed</p>
                                </div>
                            @endforeach
                        @else
                            <p>No completed practice yet.</p>
                        @endif
                    </div>
                </div> <!-- end tab content -->

            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div>

    <style>
        td.click-view {
            color: #ff5b5b;
            font-weight: 600;
        }

        h5.check-label {
            font-size: 22px;
            margin-top: 11px;
            margin-left: 15px;
        }

        .page-link.active,
        .active > .page-link {
            z-index: 3;
            color: var(--ct-pagination-active-color);
            background-color: #ff5b5b;
            border-color: #ff5b5b;
        }

        .card-body {
            padding: 7px;
        }

        i.ri-price-tag-3-fill.second {
            color: #ff5b5b;
        }

        i.ri-price-tag-3-fill.third {
            color: blue;
        }

        i.ri-price-tag-3-fill.fourth {
            color: green;
        }
    </style>
@endsection

@section('scripts')
    @vite(['resources/js/components/table-datatable.js'])
    @vite(['resources/js/pages/dashboard.js'])
@endsection
