@extends('layouts.vertical', ['title' => 'Vocabulary', 'topbarTitle' => 'Vocabulary'])

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row">

                <!-- Left Section -->
                <div class="space-y-4">
                    <h2 class="text-xl font-bold" style="font-size:20px; font-weight:bold; margin:15px 0px;">My Words</h2>
                    <div class="row g-3">

                        <!-- My Words Card -->
                        <div class="col-lg-6">
                            <a href="{{ route('vocabulary.my-words') }}" style="text-decoration:none; color:inherit;">
                                <div
                                    style="background:#fff; border:1px solid #ddd; border-radius:8px; padding:15px; box-shadow:0 2px 5px rgba(0,0,0,0.1);">

                                    <!-- Icon + Title -->
                                    <div style="display:flex; align-items:center; margin-bottom:8px;">
                                        <div style="font-size:22px; margin-right:8px;">📖</div>
                                        <div style="font-weight:600; color:#333;">My Words</div>
                                    </div>

                                    <!-- Memorized count -->
                                    <p style="margin:0 0 8px 0; font-size:14px; color:#666;">
                                        Memorized {{ $myWord_memorized }}/{{ $myWords }}
                                    </p>

                                    <!-- Continue link -->
                                    <div
                                        style="display:flex; justify-content:space-between; align-items:center; font-size:14px; color:#ffc107; font-weight:500;">
                                        <span>Continue...</span>
                                        <span>→</span>
                                    </div>

                                    <!-- Progress bar -->
                                    @php
                                        $my_words_progress =
                                            $myWords > 0 ? round(($myWord_memorized / $myWords) * 100, 2) : 0;
                                    @endphp
                                    <div
                                        style="width:100%; background:#eee; border-radius:5px; height:6px; margin-top:10px;">
                                        <div
                                            style="background:#ffc107; height:6px; border-radius:5px; width: {{ $my_words_progress }}%;">
                                        </div>
                                    </div>
                                    <small style="font-size:12px; color:#666;">{{ $my_words_progress }}%</small>
                                </div>
                            </a>
                        </div>

                        <!-- CCL Words Card -->
                        <div class="col-lg-6">
                            <a href="{{ route('vocabulary.ccl-words') }}" style="text-decoration:none; color:inherit;">
                                <div
                                    style="background:#fff; border:1px solid #ddd; border-radius:8px; padding:15px; box-shadow:0 2px 5px rgba(0,0,0,0.1);">

                                    <!-- Icon + Title -->
                                    <div style="display:flex; align-items:center; margin-bottom:8px;">
                                        <div style="font-size:22px; margin-right:8px;">📖</div>
                                        <div style="font-weight:600; color:#333;">CCL Words</div>
                                    </div>

                                    <!-- Opened count -->
                                    <p style="margin:0 0 8px 0; font-size:14px; color:#666;">
                                        Opened {{ $ccl_words_opened }}/{{ $ccl_words }}
                                    </p>

                                    <!-- Continue link -->
                                    <div
                                        style="display:flex; justify-content:space-between; align-items:center; font-size:14px; color:#ffc107; font-weight:500;">
                                        <span>Continue...</span>
                                        <span>→</span>
                                    </div>

                                    <!-- Progress bar -->
                                    @php
                                        $words_progress =
                                            $ccl_words > 0 ? round(($ccl_words_opened / $ccl_words) * 100, 2) : 0;
                                    @endphp
                                    <div
                                        style="width:100%; background:#eee; border-radius:5px; height:6px; margin-top:10px;">
                                        <div
                                            style="background:#ffc107; height:6px; border-radius:5px; width: {{ $words_progress }}%;">
                                        </div>
                                    </div>
                                    <small style="font-size:12px; color:#666;">{{ $words_progress }}%</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Section -->
                <div class="md:col-span-2">
                    <h2 style="font-size:20px; font-weight:bold; margin:15px 0px;">By Category</h2>
                    <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(250px,1fr)); gap:15px;">
                        @foreach ($categories as $category)
                            <a href="{{ route('vocabulary.words', $category->id) }}"
                                style="text-decoration:none; color:inherit;">
                                <div
                                    style="background:#fff; border:1px solid #ddd; border-radius:8px; padding:15px; box-shadow:0 2px 5px rgba(0,0,0,0.1);">

                                    <!-- Icon + Title -->
                                    <div style="display:flex; align-items:center; margin-bottom:8px;">
                                        <div style="font-size:22px; margin-right:8px;">📘</div>
                                        <div style="font-weight:600; color:#333;">{{ $category->name }}</div>
                                    </div>

                                    <!-- Opened count -->
                                    <p style="margin:0 0 8px 0; font-size:14px; color:#666;">
                                        Opened {{ $category->viewed_count }}/{{ $category->words_count }}
                                    </p>

                                    <!-- Continue link -->
                                    <div
                                        style="display:flex; justify-content:space-between; align-items:center; font-size:14px; color:#ffc107; font-weight:500;">
                                        <span>Continue...</span>
                                        <span>→</span>
                                    </div>

                                    <!-- Progress bar -->
                                    @php
                                        $progress =
                                            $category->words_count > 0
                                                ? round(($category->viewed_count / $category->words_count) * 100, 2)
                                                : 0;
                                    @endphp
                                    <div
                                        style="width:100%; background:#eee; border-radius:5px; height:6px; margin-top:10px;">
                                        <div
                                            style="background:#ffc107; height:6px; border-radius:5px; width: {{ $progress }}%;">
                                        </div>
                                    </div>
                                    <small style="font-size:12px; color:#666;">{{ $progress }}%</small>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <style>
        .custom-card {
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            border: none;
            font-family: 'Arial', sans-serif;
        }

        .custom-icon {
            font-size: 28px;
            color: #b68c2d;
            /* gold-ish color */
        }

        .custom-title {
            color: #b68c2d;
            font-weight: 600;
            font-size: 18px;
            margin-left: 8px;
        }

        .custom-progress {
            height: 6px;
            border-radius: 5px;
        }

        .custom-progress .progress-bar {
            background-color: #b68c2d;
            /* custom progress bar color */
        }

        .custom-link {
            font-size: 14px;
            font-weight: 500;
            color: #b68c2d;
            text-decoration: none;
        }

        .custom-link:hover {
            text-decoration: underline;
        }
    </style>
@endsection

@section('scripts')
    @vite(['resources/js/pages/dashboard.js'])
@endsection
