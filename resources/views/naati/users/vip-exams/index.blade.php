@extends('layouts.vertical', ['title' => 'Vip Exam Material', 'topbarTitle' => 'Vip Exam Material'])

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header border-bottom border-dashed d-flex align-items-center">
                        <h4 class="header-title">Question List</h4>
                    </div>

                    <div class="card-body">

                        <ul class="nav nav-tabs nav-justified nav-bordered nav-bordered-danger mb-3">
                            <li class="nav-item">
                                <a href="#vip-exam-tab" data-bs-toggle="tab" aria-expanded="true"
                                   class="nav-link active">
                                    <i class="ti ti-home fs-18 me-md-1"></i>
                                    <span class="d-none d-md-inline-block">Vip Exam Dialogues</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#completed-exam-tab" data-bs-toggle="tab" aria-expanded="false"
                                   class="nav-link">
                                    <i class="ti ti-user-circle fs-18 me-md-1"></i>
                                    <span class="d-none d-md-inline-block">Completed</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            {{-- Vip Exam Dialogues Tab --}}
                            <div class="tab-pane show active" id="vip-exam-tab">
                                @if ($dialogues->count())
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
                                        @foreach ($dialogues as $dialogue)
                                            @php
                                                $isLocked = !in_array($dialogue->id, $allowedDialogues ?? []);
                                            @endphp
                                            <tr class="{{ $isLocked ? 'blurred-row' : '' }}">
                                                <td>{{ $dialogue->title }}</td>
                                                <td class="click-view">
                                                    @if (!$isLocked)
                                                        <a href="{{ route('vip-exam-segments', $dialogue->id) }}">
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
                                                        <i class="ri-price-tag-3-fill"></i>
                                                    @endif
                                                </td>
                                                <td>{{ $dialogue->score ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="text-center">No Vip Exam uploaded yet</p>
                                @endif
                            </div>

                            {{-- Completed Tab --}}
                            <div class="tab-pane" id="completed-exam-tab">
                                @if ($completedDialogues->count())
                                    <table class="table table-bordered table-striped dt-responsive nowrap w-100 text-center">
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
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="text-center">No completed dialogues yet</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        td.click-view {
            color: #ff5b5b;
            font-weight: 600;
        }

        .page-link.active,
        .active>.page-link {
            background-color: #ff5b5b;
            border-color: #ff5b5b;
        }

        .card-body {
            padding: 7px;
        }

        .blurred-row {
            filter: blur(2px);
            pointer-events: none;
            opacity: 0.6;
        }

        .locked-text {
            color: #aaa;
            font-weight: bold;
            cursor: not-allowed;
        }
    </style>
@endsection

@section('scripts')
    @vite(['resources/js/pages/dashboard.js'])
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
@endsection
