@extends('layouts.vertical', ['title' => 'Practice Dialogue', 'topbarTitle' => 'Practice Dialogue'])

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="mocktest-main-box mb-3">
            <div class="mocktest-name">
                <h2 class="language-title">Dialogue: {{ $dialogue->title }}</h2>
            </div>
            <div class="dialogue">
                <p class="dialogue-desc">{{ $dialogue->description }}</p>
            </div>

            @foreach ($dialogue->admin_segments as $key => $adminSegment)
                @php
                    $segNum = $key + 1;
                    $userSegment = collect($dialogue->user_segments)->firstWhere('segment_number', $segNum);
                @endphp

                <div class="mocktest-box mb-3">
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
                                                "{{ asset('storage/' . $adminSegment->segment_path) }}"
                                            );
                                        });
                                    </script>
                                @else
                                    <p>No audio available</p>
                                @endif
                            </div>

                            <!-- User Response Audio -->
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
                                                "{{ asset('storage/' . $userSegment->segment_path) }}"
                                            );
                                        });
                                    </script>
                                @else
                                    <p>No recording submitted</p>
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
                                <p class="dialogue-desc answers">{{ $adminSegment->answer_other_language ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/wavesurfer.js"></script>
<script>
    function playPause(id) {
        // Pause all players except the one being played
        for (let key in window) {
            if (key.startsWith('wavesurfer_') && key !== 'wavesurfer_' + id && window[key].isPlaying()) {
                window[key].pause();
            }
        }
        // Toggle play/pause on the selected player
        let ws = window['wavesurfer_' + id];
        if (ws) {
            ws.playPause();
        }
    }
</script>
@endsection
