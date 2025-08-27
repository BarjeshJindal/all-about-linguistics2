@extends('layouts.vertical', ['title' => 'Mock Test List', 'topbarTitle' => 'Mock Test List'])

@section('content')
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header border-bottom border-dashed d-flex align-items-center">
                <h4 class="header-title">Mock Test List</h4>
            </div>

            <div class="card-body p-2">

                <ul class="nav nav-tabs nav-justified nav-bordered nav-bordered-danger mb-3">
                    <li class="nav-item">
                        <a href="#home-b2" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                            <i class="ti ti-parking-circle fs-18 me-md-1"></i>
                            <span class="d-none d-md-inline-block">Pending</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#profile-b2" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            <i class="ti ti-circle-dashed-check fs-18 me-md-1"></i>
                            <span class="d-none d-md-inline-block">Reviewed</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    {{-- Practice Tab --}}
                    <div class="tab-pane show active" id="home-b2">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <table id="datatable-buttons"
                                            class="table table-striped dt-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th>User Name</th>
                                                    <th>Mock Test Name</th>
                                                    <th>View</th>
                                                    <th>Score</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tbody>
                                                @forelse($pending as $mockTest)
                                                    <tr>
                                                        <td>{{ $mockTest->user_name }}</td>
                                                        <td>{{ $mockTest->mock_test_name }}</td>
                                                        <td class="click-view">
                                                            <a
                                                                href="{{ route('admin.mock-tests.reviews.show', $mockTest->id) }}">
                                                                Click to view
                                                            </a>
                                                        </td>
                                                        <td>Not yet given</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">No pending mock tests</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>

                                            </tbody>

                                        </table>

                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
                        </div> <!-- end row-->
                    </div>
                </div>
                {{-- awaiting reviews --}}
                <div class="tab-content">



                    <div class="tab-pane" id="profile-b2">
                        <table class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>Mock Test Name</th>
                                    <th>View</th>
                                    <th>Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviewed as $mockTest)
                                    <tr>
                                        <td>{{ $mockTest->user_name }}</td>
                                        <td>{{ $mockTest->mock_test_name }}</td>
                                        <td class="click-view">
                                            <a href="{{ route('admin.mock-tests.reviews.show', $mockTest->id) }}">
                                                Click to view
                                            </a>
                                        </td>
                                        <td>{{ $mockTest->score }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No reviewed mock tests</td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>

                </div> <!-- end tab content -->

            </div> <!-- end card-body -->
        </div> <!-- end card-->
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
