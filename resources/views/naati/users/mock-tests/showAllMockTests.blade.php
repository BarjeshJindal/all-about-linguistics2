@extends('layouts.vertical', ['title' => 'Mock Tests', 'topbarTitle' => 'Mock Tests'])

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
                            <span class="d-none d-md-inline-block">Practice</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#profile-b2" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            <i class="ti ti-user-circle fs-18 me-md-1"></i>
                            <span class="d-none d-md-inline-block">Awaiting Feedback</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#settings-b2" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            <i class="ti ti-settings fs-18 me-md-1"></i>
                            <span class="d-none d-md-inline-block"> Feedback Given</span>
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

                                       @if ($mockTests->count())
    <h5 class="mb-2">Mock Tests</h5>
    <table class="table table-bordered table-striped dt-responsive nowrap w-100 text-center">
        <thead>
            <tr>
                <th>Name</th>
                <th>View</th>
                <th>Tag</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mockTests as $mockTest)
                @php
                    $isLocked = !in_array($mockTest->id, $allowedDialogues ?? []);
                @endphp
                <tr class="{{ $isLocked ? 'blurred-row' : '' }}">
                    <td>{{ $mockTest->title }}</td>
                    <td class="click-view">
                        @if (!$isLocked)
                            <a href="{{ route('user.MockTest.view', $mockTest->id) }}">
                                Click to view
                            </a>
                        @else
                            <span class="locked-text">Locked</span>
                        @endif
                    </td>
                    <td>
                        @if ($isLocked)
                            <i class="ri-lock-fill text-danger"></i>
                        @else
                            <i class="ri-price-tag-3-fill second"></i>
                        @endif
                    </td>
                    <td>
                        @if (!$isLocked)
                            {{ $mockTest->score ?? '-' }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p class="text-center">No Mock Tests available yet</p>
@endif


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                {{-- awaiting reviews --}}
                <div class="tab-content">
                    <div class="tab-pane" id="profile-b2">
                        @if ($awaitingFeedback->count())
                        <table class="table table-bordered table-striped dt-responsive nowrap w-100 text-center">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>DateTime</th>
                                    <th>Status</th>
                                    <th>Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Awaiting Feedback --}}
                                @foreach ($awaitingFeedback as $feedback)
                                    <tr>
                                        <td>{{ $feedback->title ?? 'N/A' }}</td>
                                        <td>{{ $feedback->created_at ? $feedback->created_at->format('d M Y, h:i A') : '-' }}
                                        </td>
                                        <td><span class="badge bg-warning">Awaiting Feedback</span></td>
                                        <td>-</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
        <p class="text-center">No awaiting feedback.</p>
    @endif
                    </div>


                    {{-- Completed Practice Tab --}}
                    <div class="tab-pane" id="settings-b2">
                        @if ($completedFeedback->count())
                        <table id="datatable-buttons"
                            class="table table-bordered table-striped dt-responsive nowrap w-100 text-center">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>DateTime</th>
                                    <th>View</th>
                                    <th>Status</th>
                                    <th>Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Completed Feedback --}}
                                @foreach ($completedFeedback as $feedback)
                                    <tr>
                                        <td>{{ $feedback->title ?? 'N/A' }}</td>
                                        <td>{{ $feedback->created_at ? $feedback->created_at->format('d M Y, h:i A') : '-' }}
                                        </td>
                                        <td class="click-view">
                                            <a href="{{ route('user.MockTest.feedbackview', $feedback->id) }}">
                                                Click to view
                                            </a>
                                        </td>
                                        <td><span class="badge bg-success">Completed</span></td>
                                        <td>{{ $feedback->score ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <p class="text-center">No feedback given yet</p>
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
        .blurred-row td {
            filter: blur(3px);
            opacity: 0.6;
            pointer-events: none;
            user-select: none;
        }

        .locked-text {
            color: #ff5b5b;
            font-weight: bold;
        }
    </style>
@endsection

@section('scripts')
    @vite(['resources/js/pages/dashboard.js'])
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
@endsection
