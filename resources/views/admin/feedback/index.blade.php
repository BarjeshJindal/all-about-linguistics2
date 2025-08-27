@extends('layouts.vertical', ['title' => 'Feedback', 'topbarTitle' => 'Feedback'])

@section('content')
    <div class="container">
        <h2 class="mb-4">User Recordings </h2>

        {{-- Session Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Display validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @forelse ($user_recordings as $user_recording)
            <div class="card mb-4">
                <div class="card-body">
                    <h5> Name: {{ $user_recording->user->name }} | Title: {{ $user_recording->segment->segment_parent_id ?? 'N/A' }}
                    </h5>

                    <div>
                        <strong>User Recording:</strong>
                        <div id="waveform-{{ $user_recording->id }}" class="mb-2" style="width: 100%;"></div>

                        {{-- Play / Pause Button --}}
                        <button class="btn btn-outline-primary btn-sm mb-2"
                            onclick="wavesurfers[{{ $user_recording->id }}].playPause()">
                            ▶️ Play / Pause
                        </button>
                        <br>
                        <small>Duration: <span id="duration-{{ $user_recording->id }}"></span></small>
                    </div>

                    <form action="{{ route('admin.feedback.update', $user_recording->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="mt-2">
                            <label for="score-{{ $user_recording->id }}" class="form-label">Score</label>
                            <input type="number" name="score" id="score-{{ $user_recording->id }}" class="form-control"
                                value="{{ $user_recording->score ?? '' }}" required>
                        </div>

                        <div class="mt-2">
                            <label for="feedback-{{ $user_recording->id }}" class="form-label">Feedback</label>
                            <textarea name="feedback" id="feedback-{{ $user_recording->id }}" class="form-control" rows="2">{{ $user_recording->feedback ?? '' }}</textarea>
                        </div>

                        <button class="btn btn-success mt-3" type="submit">Submit Feedback</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="alert alert-info">No user recordings available for feedback.</div>
        @endforelse
    @endsection

    @section('scripts')
        <script src="https://unpkg.com/wavesurfer.js@7/dist/wavesurfer.min.js"></script>
        <script>
            const wavesurfers = {};
            @foreach ($user_recordings as $user_recording)
                wavesurfers[{{ $user_recording->id }}] = WaveSurfer.create({
                    container: '#waveform-{{ $user_recording->id }}',
                    waveColor: '#9f9f9f',
                    progressColor: '#ff4500',
                    barWidth: 1,
                    barGap: 2,
                    height: 60,
                    cursorColor: '#ccc',
                    responsive: true,
                    hideScrollbar: true,
                });

                wavesurfers[{{ $user_recording->id }}].load("{{ asset('storage/' . $user_recording->audio_path) }}");

                wavesurfers[{{ $user_recording->id }}].on('ready', function() {
                    const duration = wavesurfers[{{ $user_recording->id }}].getDuration();
                    document.getElementById('duration-{{ $user_recording->id }}').innerText =
                        `${Math.round(duration)}s`;
                });
            @endforeach
        </script>
    @endsection
