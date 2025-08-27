@extends('layouts.vertical', ['title' => 'Vip Exam Material', 'topbarTitle' => 'Vip Exam Material'])

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
                            <span class="d-none d-md-inline-block">Vip Exam Dialogues</span>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a href="#profile-b2" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            <i class="ti ti-user-circle fs-18 me-md-1"></i>
                            <span class="d-none d-md-inline-block">Awaiting Feedback</span>
                        </a>
                    </li> --}}
                    <li class="nav-item">
                        <a href="#settings-b2" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            <i class="ti ti-settings fs-18 me-md-1"></i>
                            <span class="d-none d-md-inline-block"> Completed </span>
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

                                       

                                        @if ($dialogues->count())
                                            <table id="datatable-buttons"
                                                class="table table-striped dt-responsive nowrap w-100">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>View</th>
                                                        <th>Tag</th>
                                                        <th>Score</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($dialogues as $dialogue)
                                                        <tr>
                                                            <td>{{ $dialogue->title }}</td>
                                                            <td class="click-view">
                                                                <a href="{{ route('vip-exam-segments', $dialogue->id) }}">
                                                                    Click to view
                                                                </a>
                                                            </td>
                                                            <td><i class="ri-price-tag-3-fill"></i></td>
                                                            <td>#61</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p class="text-center">No Vip Exam uploaded yet</p>
                                        @endif
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
                        </div> <!-- end row-->
                    </div>
                </div>
                {{-- awaiting reviews --}}
                <div class="tab-content">



                    {{-- <div class="tab-pane" id="profile-b2">
                        @if ($awaitingReviews->count())
                            <table class="table table-striped dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>Practice Title</th>
                                        <th>Feedback</th>
                                        <th>View</th>
                                        <th>Tag</th>
                                        <th>Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($awaitingReviews as $recording)
                                        <tr>
                                            <td>{{ $recording->segment->practice->title ?? 'N/A' }}</td>
                                            <td>Not yet given ...</td>
                                            <td>
                                                <a href="{{ route('pendingFeedback', $recording->segment->parent_id) }}">
                                                    Click to View
                                                </a>
                                            </td>
                                            <td><i class="ri-price-tag-3-fill"></i></td>
                                            <td>Not yet given ...</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-center">No awaiting feedback.</p>
                        @endif
                    </div> --}}





                    {{-- Completed Practice Tab --}}
                    <div class="tab-pane" id="settings-b2">
                        @if ($completedDialogues->count())
                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        {{-- <th>Feedback</th> --}}
                                        <th>View</th>
                                        <th>Tag</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($completedDialogues as $dialogue)
                                         <tr>
                                        <td>{{ $dialogue->title ?? 'N/A' }}</td>
                                        <td>{{ $dialogue->created_at ? $dialogue->created_at->format('d M Y, h:i A') : '-' }}</td>
                                        <td class="click-view">
                                            <a href="{{ route('results-VipExam', $dialogue->id) }}">
                                                Click to view
                                            </a>
                                        </td>
                                        <td><span class="badge bg-success">Completed</span></td>
                                        <td>{{ $dialogue->score ?? 'N/A' }}</td>
                                        <td>{{ $dialogue->score ?? 'N/A' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-center">No completed dialogues yet</p>
                        @endif
                    </div>

                </div> <!-- end tab content -->

            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div>

    <style>
        .labels {
            display: flex;
        }

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
        .active>.page-link {
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
    @vite(['resources/js/pages/dashboard.js'])
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
@endsection
