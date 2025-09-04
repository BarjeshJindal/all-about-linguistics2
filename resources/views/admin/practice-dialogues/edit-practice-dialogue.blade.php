@extends('layouts.vertical', ['title' => 'Edit Practice Dialogue', 'topbarTitle' => 'Edit Practice Dialogue'])

@section('content')
<script src="https://unpkg.com/wavesurfer.js"></script>
<div class="card shadow-sm border-0">
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <ul>
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.pratice-dialogue.update',$practiceDialogue->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" value="{{ $practiceDialogue->title}}" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="5">{{ $practiceDialogue->description}}</textarea>
            </div>

            <!-- ✅ One wrapper only -->
            <div id="segment-wrapper">
                @foreach($practiceSegments as $loopIndex => $practiceSegment)
           
                    <div class="segment-block mb-4 mt-4 border p-6 rounded-2xl bg-light">
                        <h4 class="segment-title p-2">Segment {{ $loop->iteration }}</h4>

                        <input type="hidden" name="segments[{{ $loopIndex }}][id]" value="{{ $practiceSegment->id }}">

                        <div class="mb-1 p-2">
                            <div id="waveform-{{ $practiceSegment->id }}" style="width: 100%; height: 80px;"></div>
                            <button type="button" class="btn btn-primary play-btn" data-id="segment-{{ $practiceSegment->id }}">▶ Play</button>

                            <label class="form-label audio-file">Audio File (MP3)</label>
                            {{-- @if($practiceSegment->segment_path)
                                <p>Current File:
                                    <a href="{{ asset('storage/'.$practiceSegment->segment_path) }}" target="_blank">Listen</a>
                                </p>
                            @endif --}}
                            <input type="file" name="segments[{{ $loopIndex }}][segment_path]" class="form-control" accept=".mp3,.wav">
                        </div>

                        <div class="mb-1 p-2">
                            <div id="sample-waveform-{{ $practiceSegment->id }}" style="width: 100%; height: 80px;"></div>
                            <button type="button" class="btn btn-success play-btn" data-id="sample-{{ $practiceSegment->id }}">▶ Play</button>

                            <label class="form-label">Sample Response (MP3)</label>
                            {{-- @if($practiceSegment->sample_response)
                                <p>Current Sample:
                                    <a href="{{ asset('storage/'.$practiceSegment->sample_response) }}" target="_blank">Listen</a>
                                </p>
                            @endif --}}
                            <input type="file" name="segments[{{ $loopIndex }}][sample_response]" class="form-control" accept=".mp3,.wav">
                        </div>

                        <div class="row p-2">
                            <div class="mb-1 p-2 col-sm-6">
                                <label class="form-label answer">Answer (English)</label>
                                <input type="text" name="segments[{{ $loopIndex }}][answer_eng]" value="{{ $practiceSegment->answer_eng }}" class="form-control" required>
                            </div>
                            <div class="mb-1 p-2 col-sm-6">
                                <label class="form-label answer-languages-label">Answer Second Language</label>
                                <input type="text" name="segments[{{ $loopIndex }}][answer_second_language]" value="{{ $practiceSegment->answer_other_language }}" class="form-control" required>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Add Segment Button -->
            <button type="button" class="btn btn-success mt-3" id="add-segment">+ Add Segment</button>

            <button type="submit" class="btn btn-warning mt-3">Update</button>
        </form>
    </div>
</div>

<script>
    const wavesurfers = {};
    let segmentIndex = {{ count($practiceSegments) }}; // start from existing count

    @foreach($practiceSegments as $practiceSegment)
    wavesurfers['segment-{{ $practiceSegment->id }}'] = WaveSurfer.create({
        container: '#waveform-{{ $practiceSegment->id }}',
        waveColor: '#9f9f9f',
        progressColor: '#ff4500',
        height: 60,
        
    });
    wavesurfers['segment-{{ $practiceSegment->id }}'].load("{{ asset('storage/'.$practiceSegment->segment_path) }}");

        @if($practiceSegment->sample_response)
            wavesurfers['sample-{{ $practiceSegment->id }}'] = WaveSurfer.create({
                container: '#sample-waveform-{{ $practiceSegment->id }}',
                waveColor: '#a0d2eb',
                progressColor: '#10c469',
                height: 60,
               
            });
            wavesurfers['sample-{{ $practiceSegment->id }}'].load("{{ asset('storage/'.$practiceSegment->sample_response) }}");
        @endif
    @endforeach



    // Play/pause handler
    document.querySelectorAll('.play-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            Object.keys(wavesurfers).forEach(key => { if (key !== id) wavesurfers[key].pause(); });
            wavesurfers[id].playPause();
        });
    });

    // ✅ Add Segment Function
    document.getElementById('add-segment').addEventListener('click', function() {
        const wrapper = document.getElementById('segment-wrapper');

        const newBlock = document.createElement('div');
        newBlock.classList.add('segment-block', 'mb-4', 'mt-4', 'border', 'p-6', 'rounded-2xl', 'bg-light');
        newBlock.innerHTML = `
            <h4 class="segment-title p-2">Segment ${segmentIndex + 1}</h4>
            <div class="mb-3 p-2">
                <label class="form-label">Audio File (MP3)</label>
                <input type="file" name="segments[${segmentIndex}][segment_path]" class="form-control" accept=".mp3,.wav" >
            </div>
            <div class="mb-1 p-2">
                <label class="form-label">Sample Response (MP3)</label>
                <input type="file" name="segments[${segmentIndex}][sample_response]" class="form-control" accept=".mp3,.wav">
            </div>
            <div class="row p-2">
                <div class="mb-3 p-2 col-sm-6">
                    <label class="form-label">Answer (English)</label>
                    <input type="text" name="segments[${segmentIndex}][answer_eng]" class="form-control" required>
                </div>
                <div class="mb-3 p-2 col-sm-6">
                    <label class="form-label answer-languages-label">Answer Second Language</label>
                    <input type="text" name="segments[${segmentIndex}][answer_second_language]" class="form-control" required>
                </div>
            </div>
        `;
        wrapper.appendChild(newBlock);
        segmentIndex++;
    });
</script>
@endsection
