@extends('layouts.vertical', ['title' => 'Dashboard', 'topbarTitle' => 'Dashboard'])

@section('content')
    <div class="container dashboard-background">
        <div class="row">

            <div class="col-xl-6 col-md-6">
                <div class="row">
                    <div class="col-xxl-6 col-xl-6">
                        <div class="col">
                            <div class="card">
                                <div class="d-flex card-header justify-content-between align-items-center">
                                    <div>
                                        <h4 class="header-title">Practice Dialogue</h4>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    @php
                                        $percentageCompleted = 0;
                                        if ($practicedialogueCount > 0) {
                                            $percentageCompleted = round(
                                                ($completedPracticeDialogue / $practicedialogueCount) * 100,
                                                2,
                                            );
                                        }

                                    @endphp
                                    <div class="d-flex align-items-end gap-2 justify-content-between">
                                        <div class="text-end flex-shrink-0">
                                            <div id="total-orders-chart" data-colors="#ff5b5b,#F6F7FB"
                                                data-completed="{{ $completedPracticeDialogue ?? 0 }}"
                                                data-total="{{ $practicedialogueCount ?? 0 }}">
                                            </div>
                                            <h3 class="completed">Completed:
                                                {{ isset($completedPracticeDialogue) ? $completedPracticeDialogue : 0 }}
                                            </h3>
                                        </div>
                                        <div class="text-end">
                                            <h3 class="fw-semibold">{{ $practicedialogueCount }}</h3>
                                            <p class="text-muted mb-0">Total</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                    </div>

                    <div class="col-xxl-6 col-xl-6">
                        <!-- end col -->
                        <div class="col">
                            <div class="card">
                                <div class="d-flex card-header justify-content-between align-items-center">
                                    <div>
                                        <h4 class="header-title">VIP Test</h4>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-end gap-2 justify-content-between">
                                        <div class="text-end flex-shrink-0">
                                            <div id="new-users-chart" data-colors="#f9c851,#F6F7FB" data-completed="{{ $completedvipexam ?? 0 }}" data-total="{{ $vipexamCount ?? 0 }}"></div>
                                            <h3 class="completed">
                                                Completed:{{ isset($completedvipexam) ? $completedvipexam : 0 }}</h3>

                                        </div>
                                        <div class="text-end">
                                            <h3 class="fw-semibold">{{ $vipexamCount }}</h3>
                                            <p class="text-muted mb-0">Total</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                    </div>

                    <div class="col-xxl-6 col-xl-6">
                        <div class="col">
                            <div class="card">
                                <div class="d-flex card-header justify-content-between align-items-center">
                                    <div>
                                        <h4 class="header-title">Mock Test</h4>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    @php
                                        $mockpercentageCompleted = 0;
                                        if ($mocktestCount > 0) {
                                            $mockpercentageCompleted = round(
                                                ($completedMockTest / $mocktestCount) * 100,
                                                2,
                                            );
                                        }
                                    @endphp

                                    <div class="d-flex align-items-end gap-2 justify-content-between">
                                        <div class="text-end flex-shrink-0">
                                            <div id="mock-test-chart"
                                                data-colors="#10c469,#F6F7FB"
                                                data-percentage="{{ $mockpercentageCompleted ?? 0 }}">
                                            </div>
                                            <h3 class="completed">Completed: {{ $completedMockTest ?? 0 }}</h3>
                                        </div>
                                        <div class="text-end">
                                            <h3 class="fw-semibold">{{ $mocktestCount ?? 0 }}</h3>
                                            <p class="text-muted mb-0">Total</p>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-6 col-xl-6">
                        <div class="col">
                            <div class="card">
                                <div class="d-flex card-header justify-content-between align-items-center">
                                    <div>
                                        <h4 class="header-title">Vocabulary</h4>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    @php
                                        $words_percentage =
                                            $total_words > 0 ? round(($total_words_opened / $total_words) * 100, 2) : 0;
                                    @endphp
                                    <div class="d-flex align-items-center gap-2 justify-content-between">
                                        <span class="badge bg-info rounded-pill fs-13">
                                            {{ $words_percentage }} % <i class="ti ti-trending-down"></i>
                                        </span>
                                        <div class="text-end">
                                            <h3 class="fw-semibold">{{ $total_words }}</h3>
                                            <p class="text-muted mb-0">Total</p>
                                        </div>
                                    </div>
                                    <h3 class="completed">Completed: {{ $total_words_opened }}</h3>
                                    <div class="progress progress-soft progress-sm mt-3">
                                        <div class="progress-bar bg-succesds" role="progressbar"
                                            style="width: {{ $words_percentage ?? 0 }}%;"
                                            aria-valuenow="{{ $words_percentage }}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center border-bottom border-dashed">
                        <h4 class="header-title mb-0"> Category Practice Dialogues</h4>
                    </div>
                    <div class="card-body practice-dialogues-categories">
                        <div class="row">
                            @php
                                $colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info'];
                                $i = 0;
                            @endphp
                            @forelse($categories as $category)
                                <div class="col-xl-6 col-md-6">
                                    @php
                                        // Pick a color from the array based on index
                                        $color = $colors[$i % count($colors)];
                                        $i++;
                                    @endphp
                                    <h5 class="mt-0">
                                        {{ $category->name }}
                                        <span class="text-{{ $color }} float-end">
                                            {{ !empty($category->completion_percentage) ? $category->completion_percentage : '0.00' }}
                                            %
                                        </span>
                                    </h5>
                                    <h5 class="mt-0">Question</h5>
                                    <div class="progress progress-soft progress-sm mt-0 mb-3">
                                        <div class="progress-bar bg-{{ $color }}" role="progressbar"
                                            aria-valuenow="{{ $category->completion_percentage ?? 0 }}" aria-valuemin="0"
                                            aria-valuemax="100"
                                            style="width: {{ $category->completion_percentage ?? 0 }}%;">
                                        </div>
                                    </div>
                                </div> <!-- col-xl-6 col-md-6 end -->
                            @empty
                                <h5 class="mt-0 text-primary float-end"> No Categories Found</h5>
                            @endforelse
                        </div>
                    </div> <!-- end card-body -->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/pages/dashboard.js'])
@endsection
