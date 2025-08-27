<script src="https://unpkg.com/wavesurfer.js"></script>

@extends('layouts.vertical', ['title' => 'Mock Test', 'topbarTitle' => 'Mock Test'])

@section('content')


    
      <div class="card shadow-sm border-0">
        <div class="card-body">
            @foreach ($dialogues as $index => $dialogue)
                <div class="mocktest-main-box mb-3">
                    <div class="mocktest-name">
                        <h2 class="language-title">Dialogue {{ $index + 1 }}: {{ $dialogue->title }}</h2>
                    </div>
                    <div class="dialogue">
                        <p class="dialogue-desc">{{ $dialogue->description }}</p>
                    </div>

                    @php
                        $adminSegments = $dialogue->admin_segments;
                        $userSegments = $dialogue->user_segments;
                    @endphp

                    @foreach ($adminSegments as $key => $adminSegment)
                        @php
                            $segNum = $key + 1;
                            $userSegment = $userSegments[$segNum] ?? null;
                        @endphp
                        <div class="mocktest-box mb-2">
                            <h2 class="segment-title">Segment {{ $segNum }}</h2>
                            <div class="container">
                                <div class="row p-2">
                                    <!-- Original Audio -->
                                    <div class="audio col-sm-4">
                                        <h3 class="dialogue-title">Original Audio</h3>
                                        @if ($adminSegment->segment_path)
                                            <div id="waveform-admin-{{ $adminSegment->id }}"></div>
                                            <button type="button" class="btn btn-sm btn-primary my-2"
                                                onclick="playPause('admin_{{ $adminSegment->id }}')">Play / Pause</button>
                                            <script>
                                                document.addEventListener("DOMContentLoaded", function() {
                                                    window['wavesurfer_admin_{{ $adminSegment->id }}'] = WaveSurfer.create({
                                                        container: '#waveform-admin-{{ $adminSegment->id }}',
                                                        waveColor: '#999',
                                                        progressColor: '#007bff',
                                                        height: 80,
                                                        responsive: true
                                                    });
                                                    window['wavesurfer_admin_{{ $adminSegment->id }}'].load(
                                                        "{{ asset('storage/' . $adminSegment->segment_path) }}");
                                                });
                                            </script>
                                        @else
                                            <p>No audio available</p>
                                        @endif
                                    </div>

                                    <div class="audio col-sm-4">
                                        <h3 class="dialogue-title">Your Recording</h3>
                                        @if ($userSegment && $userSegment->segment_path)
                                            <div id="waveform-user-{{ $userSegment->id }}"></div>
                                            <button type="button" class="btn btn-sm btn-success mt-2"
                                                onclick="playPause('user_{{ $userSegment->id }}')">Play / Pause</button>
                                            <script>
                                                document.addEventListener("DOMContentLoaded", function() {
                                                    window['wavesurfer_user_{{ $userSegment->id }}'] = WaveSurfer.create({
                                                        container: '#waveform-user-{{ $userSegment->id }}',
                                                        waveColor: '#999',
                                                        progressColor: '#28a745',
                                                        height: 80,
                                                        responsive: true
                                                    });
                                                    window['wavesurfer_user_{{ $userSegment->id }}'].load(
                                                        "{{ asset('storage/' . $userSegment->segment_path) }}");
                                                });
                                            </script>
                                        @else
                                            <p>No Recording Available</p>
                                        @endif
                                    </div>

                                       <!-- Sample Response Audio -->
                <div class="audio col-sm-4">
                    <h3 class="dialogue-title">Sample Response</h3>
                    @if ($adminSegment->sample_response)
                        <div id="waveform-sample-{{ $adminSegment->id }}"></div>
                        <button type="button" class="btn btn-sm btn-warning mt-2"
                            onclick="playPause('sample_{{ $adminSegment->id }}')">Play / Pause</button>
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                window['wavesurfer_sample_{{ $adminSegment->id }}'] = WaveSurfer.create({
                                    container: '#waveform-sample-{{ $adminSegment->id }}',
                                    waveColor: '#999',
                                    progressColor: '#ffc107',
                                    height: 80,
                                    responsive: true
                                });
                                window['wavesurfer_sample_{{ $adminSegment->id }}'].load(
                                    "{{ asset('storage/' . $adminSegment->sample_response) }}");
                            });
                        </script>
                    @else
                        <p>No sample response</p>
                    @endif
                </div>
                


                                    
                                </div>
                            </div>

                            <!-- Answers -->
                            <div class="row p-2">
                                <div class="mb-1 p-2 col-sm-6">
                                    <label class="form-label answer">Answer (English)</label>
                                    <div class="mocktest-box">
                                        <p class="dialogue-desc answers">{{ $adminSegment->answer_eng ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="mb-1 p-2 col-sm-6">
                                    <label class="form-label answer-language-label">Answer (Other Language)</label>
                                    <div class="mocktest-box">
                                        <p class="dialogue-desc answers">
                                            {{ $adminSegment->answer_other_language ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

      {{-- feedback form --}}
            <form class="p-3 border rounded bg-light" method="POST"
                action="">
                <input type="hidden" name="id" value="{{ $mockTest->user_mock_test_id }}">

                <h2 class="feedback-title mb-3">Feedback</h2>

               
                    {{-- Show result if score is not null --}}
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Score:</strong> {{ $mockTest->score }}
                        </div>
                        <div class="col-md-9">
                            <strong>Notes:</strong> {{ $mockTest->feedback }}
                        </div>
                    </div>
            </form>

        </div>
    </div>
    

@endsection


@section('scripts')
    <script src="https://unpkg.com/wavesurfer.js"></script>
    <script>
        function playPause(id) {
            //alert('wavesurfer_' + id);
            let ws = window['wavesurfer_' + id];

            console.log(ws);
            if (ws) {
                ws.playPause();
            }
        }
    </script>
@endsection

{{-- @endsection --}}
@section('scripts')
    {{-- @vite(['resources/js/pages/dashboard.js']) --}}
@endsection


