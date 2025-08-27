<!-- Audio Player -->
<div class="bg-[#818a91] mt-4">
    <div id="waveform-{{ $segment->id }}" style="width: 100%; height: 80px;"></div>
    <div class="d-flex justify-content-between align-items-center mt-2">
        <div>
            <label class="me-1 text-muted small">🔊</label>
            <input type="range" min="0" max="1" step="0.01" value="1" id="volume-{{ $segment->id }}"
                style="width: 80px;" />

            <label class="ms-3 me-1 text-muted small">⏩</label>
            <select id="speed-{{ $segment->id }}" style="width: 70px;">
                <option value="0.75">0.75x</option>
                <option value="1" selected>1x</option>
                <option value="1.25">1.25x</option>
                <option value="1.5">1.5x</option>
                <option value="2">2x</option>
            </select>
        </div>
        <div id="duration-{{ $segment->id }}" class="text-muted small"></div>
    </div>

    <!-- Recording Section -->
    <div class="segment-container" data-segment-id="{{ $segment->id }}"
        data-segment-audio-path="{{ $segment->segment_path }}">
        <canvas width="400" height="100"></canvas>
        <div id="user-waveform-{{ $segment->id }}" class="user-recorded-waveform"></div>

        <button id="playPauseBtn-{{ $segment->id }}"
            class="playRecording bg-white border border-primary text-primary p-1 rounded-full shadow hover:bg-primary hover:text-white transition disabled:opacity-50"
            disabled>
            <i class="uil uil-play" id="playIcon-{{ $segment->id }}"></i>
        </button>

        <button type="button" onclick="takeNoteBtn()"
            class="p-2 btn btn-light-success text-success btn-sm rounded-pill me-2 d-inline-flex align-items-center justify-content-center fa-pull-right"
            style="font-weight: bold;">
            <i class="ti ti-edit fs-5 me-2"></i>Take Your Notes
        </button>

        <div>
            <span class="user-recorded-duration text-muted small"></span>
        </div>

        <div class="mt-2 d-flex segments-btn">
            <div class="start-stop-btn">
                <button class="startRecording btn btn-danger">Start</button>
                <button class="stopRecording btn btn-danger" disabled>Stop</button>
            </div>

            <!-- Answer Modal Trigger -->
            <button class="btn btn-info p-2 btn-sm show-answer" data-answer-eng="{{ $segment->answer_eng }}"
                data-answer-hindi="{{ $segment->answer_other_language }}">
                Answer
            </button>
        </div>
        <div class="d-flex justify-content-between mt-4">


            @if (!$loop->first)
                <button class="btn btn-secondary prev-tab"
                    data-target="#segment-{{ $dialogue->segments[$loop->index - 1]->id }}">
                    ⬅️ Previous
                </button>
            @else
                <span></span>
            @endif

            @if (!$loop->last)
                <button class="btn btn-secondary next-tab"
                    data-target="#segment-{{ $dialogue->segments[$loop->index + 1]->id }}">
                    Next ➡️
                </button>
            @endif
        </div>
    </div>
</div>
