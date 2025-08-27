@extends('layouts.vertical', ['title' => 'Vip Exam', 'topbarTitle' => 'Vip Exam'])

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">
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
                                // ✅ Match user segment by segment_number = admin segment id
                                $userSegment = collect($dialogue->user_segments)->firstWhere('segment_number', $adminSegment->id);
                            @endphp

                            <div class="mocktest-box mb-2">
                                <h2 class="segment-title">Segment {{ $segNum }}</h2>
                                <div class="container">
                                    <div class="row p-2">
                                        <!-- Original Audio -->
                                        <div class="audio col-sm-4">
                                            <h3 class="dialogue-title">Original Audio</h3>
                                            @if (!empty($adminSegment->segment_path))
                                                <div id="waveform-admin-{{ $adminSegment->id }}"></div>
                                                <button type="button" class="btn btn-sm btn-primary my-2"
                                                    onclick="playPause('admin_{{ $adminSegment->id }}')">
                                                    Play / Pause
                                                </button>
                                            @else
                                                <p>No audio available</p>
                                            @endif
                                        </div>

                                        <!-- User Response Audio -->
                                        <div class="audio col-sm-4">
                                            <h3 class="dialogue-title">Your Recording</h3>
                                            @if ($userSegment && !empty($userSegment->segment_path))
                                                <div id="waveform-user-{{ $userSegment->id }}"></div>
                                                <button type="button" class="btn btn-sm btn-success mt-2"
                                                    onclick="playPause('user_{{ $userSegment->id }}')">
                                                    Play / Pause
                                                </button>
                                            @else
                                                <p>No recording submitted</p>
                                            @endif
                                        </div>

                                        <!-- Sample Response Audio -->
                                        <div class="audio col-sm-4">
                                            <h3 class="dialogue-title">Sample Response</h3>
                                            @if (!empty($adminSegment->sample_response))
                                                <div id="waveform-sample-{{ $adminSegment->id }}"></div>
                                                <button type="button" class="btn btn-sm btn-warning mt-2"
                                                    onclick="playPause('sample_{{ $adminSegment->id }}')">
                                                    Play / Pause
                                                </button>
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
                                                {{ $adminSegment->answer_other_language ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/wavesurfer.js"></script>
    @php
        $audioData = [
            'admin' => collect($dialogue->admin_segments)->mapWithKeys(function ($seg) {
                return [$seg->id => $seg->segment_path];
            }),
            'sample' => collect($dialogue->admin_segments)->mapWithKeys(function ($seg) {
                return [$seg->id => $seg->sample_response];
            }),
            'user' => collect($dialogue->user_segments)->mapWithKeys(function ($seg) {
                return [$seg->id => $seg->segment_path];
            }),
        ];
    @endphp

    <script>
        const audioData = @json($audioData);

        const wavePlayers = {};

        document.addEventListener("DOMContentLoaded", () => {
            // Initialize all WaveSurfer instances
            for (const [type, segments] of Object.entries(audioData)) {
                for (const [id, path] of Object.entries(segments)) {
                    if (!path) continue;
                    const containerId = `#waveform-${type}-${id}`;
                    wavePlayers[`${type}_${id}`] = WaveSurfer.create({
                        container: containerId,
                        waveColor: '#999',
                        progressColor: type === 'admin' ? '#007bff' : type === 'user' ? '#28a745' : '#ffc107',
                        height: 80,
                        responsive: true
                    });
                    wavePlayers[`${type}_${id}`].load(`{{ asset('storage') }}/${path}`);
                }
            }
        });

        function playPause(key) {
            const player = wavePlayers[key];
            if (player) {
                player.playPause();
            }
        }
    </script>
@endsection
