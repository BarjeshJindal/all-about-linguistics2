@extends('layouts.vertical', ['title' => 'Edit Mock Test', 'topbarTitle' => 'Edit Mock Test'])

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

        <form action="{{ route('admin.mock-tests.update',$mocktest->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Mock Test Info -->
            <div class="mb-3">
                <label class="form-label">Mock Test Title</label>
                <input type="text" value="{{ $mocktest->title }}" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Duration (minutes)</label>
                <input type="number" value="{{ $mocktest->duration }}" name="duration" class="form-control" required>
            </div>

            <hr>
            <!-- Dialogue One -->
            <h3>Dialogue One</h3>
            @php $dialogueOne = $dialogues->firstWhere('id', $mocktest->dialogue_one_id); @endphp
            <div class="mb-3">
                <label class="form-label">Dialogue Title</label>
                <input type="text" name="dialogue_one_title" value="{{ $dialogueOne?->title }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Dialogue Description</label>
                <textarea name="dialogue_one_description" class="form-control" rows="4">{{ $dialogueOne?->description }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Translation Flow</label>
                <select name="dialogue_one_flow" class="form-control">
                    <option value="english_to_other" {{ $dialogueOne?->translation_flow == 'english_to_other' ? 'selected' : '' }}>English to Other</option>
                    <option value="other_to_english" {{ $dialogueOne?->translation_flow == 'other_to_english' ? 'selected' : '' }}>Other to English</option>
                </select>
            </div>

            <!-- Dialogue One Segments -->
            <h4>Dialogue One Segments</h4>
            <div id="dialogue-one-segments">
                @foreach($segments->where('dialogue_id', $mocktest->dialogue_one_id) as $loopIndex => $segment)
                    <div class="segment-block mb-4 mt-4 border p-6 rounded-2xl bg-light">
                        <h5 class="segment-title p-2">Segment {{ $loop->iteration }}</h5>
                        <button type="button" class="btn btn-danger mt-2 remove-segment-btn" data-id="{{ $segment->id }}">❌ Remove</button>
                        <input type="hidden" name="segments[{{ $loopIndex }}][id]" value="{{ $segment->id }}">
                        <input type="hidden" name="segments[{{ $loopIndex }}][dialogue_id]" value="{{ $mocktest->dialogue_one_id }}">

                        <!-- Segment audio -->
                        <div class="mb-1 p-2">
                            <div id="waveform-{{ $segment->id }}" style="width: 100%; height: 80px;"></div>
                            <button type="button" class="btn btn-primary play-btn" data-id="segment-{{ $segment->id }}">▶ Play</button>
                            <input type="file" name="segments[{{ $loopIndex }}][segment_path]" class="form-control mt-2" accept=".mp3,.wav">
                        </div>

                        <!-- Sample response -->
                        <div class="mb-1 p-2">
                            <div id="sample-waveform-{{ $segment->id }}" style="width: 100%; height: 80px;"></div>
                            <button type="button" class="btn btn-success play-btn" data-id="sample-{{ $segment->id }}">▶ Play</button>
                            <input type="file" name="segments[{{ $loopIndex }}][sample_response]" class="form-control mt-2" accept=".mp3,.wav">
                        </div>

                        <!-- Answers -->
                        <div class="row p-2">
                            <div class="mb-1 p-2 col-sm-6">
                                <label class="form-label">Answer (English)</label>
                                <input type="text" name="segments[{{ $loopIndex }}][answer_eng]" value="{{ $segment->answer_eng }}" class="form-control">
                            </div>
                            <div class="mb-1 p-2 col-sm-6">
                                <label class="form-label">Answer Second Language</label>
                                <input type="text" name="segments[{{ $loopIndex }}][answer_second_language]" value="{{ $segment->answer_other_language }}" class="form-control">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-sm btn-success add-segment-btn" onclick="addSegment('dialogue-one-segments', {{ $mocktest->dialogue_one_id }})">+ Add Segment</button>

            <hr>
            <!-- Dialogue Two -->
            <h3>Dialogue Two</h3>
            @php $dialogueTwo = $dialogues->firstWhere('id', $mocktest->dialogue_two_id); @endphp
            <div class="mb-3">
                <label class="form-label">Dialogue Title</label>
                <input type="text" name="dialogue_two_title" value="{{ $dialogueTwo?->title }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Dialogue Description</label>
                <textarea name="dialogue_two_description" class="form-control" rows="4">{{ $dialogueTwo?->description }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Translation Flow</label>
                <select name="dialogue_two_flow" class="form-control">
                    <option value="english_to_other" {{ $dialogueTwo?->translation_flow == 'english_to_other' ? 'selected' : '' }}>English to Other</option>
                    <option value="other_to_english" {{ $dialogueTwo?->translation_flow == 'other_to_english' ? 'selected' : '' }}>Other to English</option>
                </select>
            </div>

            <!-- Dialogue Two Segments -->
            <h4>Dialogue Two Segments</h4>
            <div id="dialogue-two-segments">
                @foreach($segments->where('dialogue_id', $mocktest->dialogue_two_id) as $loopIndex => $segment)
                    <div class="segment-block mb-4 mt-4 border p-6 rounded-2xl bg-light">
                        <h5 class="segment-title p-2">Segment {{ $loop->iteration }}</h5>
                        <button type="button" class="btn btn-danger mt-2 remove-segment-btn" data-id="{{ $segment->id }}">❌ Remove</button>
                        <input type="hidden" name="segments[{{ $loopIndex }}][id]" value="{{ $segment->id }}">
                        <input type="hidden" name="segments[{{ $loopIndex }}][dialogue_id]" value="{{ $mocktest->dialogue_two_id }}">

                        <!-- Segment audio -->
                        <div class="mb-1 p-2">
                            <div id="waveform-{{ $segment->id }}" style="width: 100%; height: 80px;"></div>
                            <button type="button" class="btn btn-primary play-btn" data-id="segment-{{ $segment->id }}">▶ Play</button>
                            <input type="file" name="segments[{{ $loopIndex }}][segment_path]" class="form-control mt-2" accept=".mp3,.wav">
                        </div>

                        <!-- Sample response -->
                        <div class="mb-1 p-2">
                            <div id="sample-waveform-{{ $segment->id }}" style="width: 100%; height: 80px;"></div>
                            <button type="button" class="btn btn-success play-btn" data-id="sample-{{ $segment->id }}">▶ Play</button>
                            <input type="file" name="segments[{{ $loopIndex }}][sample_response]" class="form-control mt-2" accept=".mp3,.wav">
                        </div>

                        <!-- Answers -->
                        <div class="row p-2">
                            <div class="mb-1 p-2 col-sm-6">
                                <label class="form-label">Answer (English)</label>
                                <input type="text" name="segments[{{ $loopIndex }}][answer_eng]" value="{{ $segment->answer_eng }}" class="form-control">
                            </div>
                            <div class="mb-1 p-2 col-sm-6">
                                <label class="form-label">Answer Second Language</label>
                                <input type="text" name="segments[{{ $loopIndex }}][answer_second_language]" value="{{ $segment->answer_other_language }}" class="form-control">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn  btn-success add-segment-btn mt-3" onclick="addSegment('dialogue-two-segments', {{ $mocktest->dialogue_two_id }})">+ Add Segment</button>

            <button type="submit" class="btn btn-warning mt-3">Update Mock Test</button>
        </form>
    </div>
</div>

<script>
    const wavesurfers = {};

    @foreach($segments as $segment)
        wavesurfers['segment-{{ $segment->id }}'] = WaveSurfer.create({
            container: '#waveform-{{ $segment->id }}',
            waveColor: '#9f9f9f',
            progressColor: '#ff4500',
            height: 60,
        });
        wavesurfers['segment-{{ $segment->id }}'].load("{{ asset('storage/'.$segment->segment_path) }}");

        @if($segment->sample_response)
            wavesurfers['sample-{{ $segment->id }}'] = WaveSurfer.create({
                container: '#sample-waveform-{{ $segment->id }}',
                waveColor: '#a0d2eb',
                progressColor: '#10c469',
                height: 60,
            });
            wavesurfers['sample-{{ $segment->id }}'].load("{{ asset('storage/'.$segment->sample_response) }}");
        @endif
    @endforeach

    // Play/pause toggle
    document.querySelectorAll('.play-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            Object.keys(wavesurfers).forEach(key => {
                if (key !== id) wavesurfers[key].pause();
            });
            wavesurfers[id].playPause();
        });
    });
     document.addEventListener("click", function(e) {
    if (e.target.classList.contains("remove-segment-btn")) {
        let segmentId = e.target.dataset.id;

        // Hide/remove the block from the DOM
        e.target.closest(".segment-block").remove();

        // Add a hidden input so backend knows which to delete
        let input = document.createElement("input");
        input.type = "hidden";
        input.name = "deleted_segments[]";
        input.value = segmentId;
        document.querySelector("form").appendChild(input);
    }
});

    // Add new segment dynamically
let segmentCounter = {{ $segments->count() }};
function addSegment(containerId, dialogueId) {
    const container = document.getElementById(containerId);
    const newIndex = segmentCounter++;

    // Get segment count inside this dialogue container (excluding removed)
    const currentCount = container.querySelectorAll('.segment-block').length + 1;

    const block = document.createElement('div');
    block.classList.add('segment-block','mb-4','mt-4','border','p-6','rounded-2xl','bg-light');
    block.innerHTML = `
        <h5 class="segment-title p-2">Segment ${currentCount}</h5>
        <button type="button" class="btn btn-danger mt-2 remove-segment-btn">❌ Remove</button>
        <input type="hidden" name="segments[${newIndex}][dialogue_id]" value="${dialogueId}">

        <div class="mb-1 p-2">
            <label>Segment Audio</label>
            <input type="file" name="segments[${newIndex}][segment_path]" class="form-control" accept=".mp3,.wav">
        </div>
        <div class="mb-1 p-2">
            <label>Sample Response</label>
            <input type="file" name="segments[${newIndex}][sample_response]" class="form-control" accept=".mp3,.wav">
        </div>
        <div class="row p-2">
            <div class="mb-1 p-2 col-sm-6">
                <label class="form-label">Answer (English)</label>
                <input type="text" name="segments[${newIndex}][answer_eng]" class="form-control">
            </div>
            <div class="mb-1 p-2 col-sm-6">
                <label class="form-label">Answer Second Language</label>
                <input type="text" name="segments[${newIndex}][answer_second_language]" class="form-control">
            </div>
        </div>
    `;
    container.appendChild(block);
}

// Reuse same remove logic for all (existing + new)
document.addEventListener("click", function(e) {
    if (e.target.classList.contains("remove-segment-btn")) {
        let block = e.target.closest(".segment-block");
        let hiddenIdInput = block.querySelector("input[name*='[id]']"); // existing ones have id

        if (hiddenIdInput) {
            // Existing segment → mark for deletion
            let input = document.createElement("input");
            input.type = "hidden";
            input.name = "deleted_segments[]";
            input.value = hiddenIdInput.value;
            document.querySelector("form").appendChild(input);
        }

        // Remove from DOM
        block.remove();
    }
});

</script>
@endsection
