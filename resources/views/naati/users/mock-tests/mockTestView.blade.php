@extends('layouts.vertical', ['title' => 'Mock Test', 'topbarTitle' => 'Mock Test'])

@section('content')


    <link href="https://unicons.iconscout.com/release/v4.0.8/css/line.css" rel="stylesheet">

    <!-- Instructions Button -->

    <div class="inst">
        <button type="button" class="btn btn-primary" id="blinking-button" data-bs-toggle="modal"
            data-bs-target="#exampleModal">
            Instructions
        </button>
        <!-- Timer & Segment Progress -->
        <div class="d-flex align-items-center my-2 gap-2">
            <div>
                <i class="ti ti-clock me-2"></i>
                <span id="countdown" class="badge bg-danger fs-6 py-2 px-3">00:00</span>
            </div>
            <div>
                <span id="segmentProgress">1 / {{ count($dialogues[0]->segments) }}</span>
            </div>
        </div>

    </div>

    

    <!-- Instructions Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Instructions</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 class="fw-bolder" style="color: #e1c21e;">{{-- $practice->second_language --}} CCL TEST</h4>
                    <p class="instruction-popup-title">To begin the segment:</p>
                    <ul>
                        <li>
                            Click <button class="btn btn-sm btn-outline-primary">▶️ Play / Pause</button> to start the
                            dialogue
                        </li>
                    </ul>
                    <p class="instruction-popup-title">To record your voice for the segment:</p>
                    <ul>
                        <li>Click the <button class="startRecording btn btn-danger">Start Recording</button> button.</li>
                        <li>Click the <button class="stopRecording btn btn-danger">Stop</button> button when done.</li>
                        <li>Click the <button class="playRecording btn btn-success">Play</button> button to listen.</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Dialogues Loop -->
    <div class="row">
        @foreach ($dialogues as $dIndex => $dialogue)
            <div class="dialogue-container row" id="dialogue-{{ $dialogue->id }}"
                style="{{ $dIndex !== 0 ? 'display:none;' : '' }}">
                <!-- Left Side: Camera & Segment Player -->
                <div class="col-lg-9 bg-camera">
                    <div class="diloce-title">
                        <h2>Dialogue {{ $dIndex + 1 }} - {{ $dialogue->title }}</h2>
                        {{-- <p class="desc">{{ $dialogue->description }}</p> --}}
                    </div>

                    <!-- Camera UI -->
                    <div id="camera-wrapper"
                        style="position: relative; width: 100%; max-width: 250px; height: 250px; border: 2px solid #ccc; border-radius: 12px; overflow: hidden;">
                        <button id="close-camera"
                            style="position: absolute; top: 10px; left: 10px; z-index: 10; background: rgba(0,0,0,0.5); color: white; border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer;">×</button>
                        <video id="camera-feed" autoplay playsinline
                            style="width: 100%; height: 100%; object-fit: cover;"></video>
                    </div>
                    <button id="reopen-camera" class="btn btn-primary mt-3" style="display: none;">Turn On Camera</button>

                    <!-- Segment Tabs Content -->
                    <div class="tab-content mt-4">
                        @foreach ($dialogue->segments as $sIndex => $segment)
                            <div class="tab-pane fade {{ $sIndex === 0 ? 'show active' : '' }}"
                                id="segment-{{ $segment->id }}">
                                @include('naati.users.mock-tests.partials.segment-player', [
                                    'segment' => $segment,
                                    'practice' => $practice,
                                ])
                            </div>
                        @endforeach
                    </div>

                    <!-- Complete Dialogue Button -->
                    <div class="mt-4 text-end">
                        <button class="btn btn-success complete-dialogue" data-current="{{ $dialogue->id }}"
                            data-next="{{ $dialogues[$dIndex + 1]->id ?? '' }}" {{ $loop->last ? 'disabled' : '' }}>
                            ✅ Complete Dialogue
                        </button>
                    </div>
                    <hr>
                </div>

                <!-- Right Side: Segment List Tabs -->
                <div class="col-lg-3 sides">
                    <div class="sidebar">
                        <ul class="nav flex-column nav-tabs" id="segmentTab-{{ $dialogue->id }}" role="tablist">
                            @foreach ($dialogue->segments as $segment)
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-start {{ $loop->first ? 'active' : '' }}"
                                        id="segment-tab-{{ $segment->id }}" data-bs-toggle="tab"
                                        href="#segment-{{ $segment->id }}" role="tab">
                                        <i class="ti ti-microphone me-2" style="color: rgb(193, 150, 23);"></i>
                                        Segment {{ $loop->iteration }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>


    <!-- Answer Modal -->
    <div class="modal fade" id="answerModal" tabindex="-1" aria-labelledby="answerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="answerModalLabel">Answer Preview</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    {{-- <h5 class="text-primary fw-bold">English Answer:</h5> --}}
                    <p id="answerEng" class="mb-3 text-dark"></p>
                    {{-- <h5 class="text-success fw-bold">$practice->second_language Answer:</h5> --}}
                    <p id="answerHindi" class="mb-0 text-dark"></p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Test Button -->
    <div class="my-4">
        <button onclick="submitAllResponses()" class="btn btn-success">Submit</button>
    </div>

    <!-- Switch Dialogue JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.complete-dialogue');

            buttons.forEach(button => {
                button.addEventListener('click', function() {

                    // 🚨 Force stop everything each time the button is clicked
                    document.querySelectorAll('audio').forEach(a => {
                        try { a.pause(); a.currentTime = 0; } catch (e) {}
                    });

                    for (let id in wavesurfers) {
                        try { wavesurfers[id].stop(); } catch (e) {}
                    }

                    if (window.currentRecorder && window.currentRecorder.state !== "inactive") {
                        try { window.currentRecorder.stop(); } catch (e) {}
                    }

                    if (window.currentMicStream) {
                        try { window.currentMicStream.getTracks().forEach(t => t.stop()); } catch (e) {}
                    }

                     // 🚨 NEW: Stop any manually created Audio objects (like your beep sound)
                    if (window.activeAudioPlayers) {
                        window.activeAudioPlayers.forEach(player => {
                            try { player.pause(); player.currentTime = 0; } catch (e) {}
                        });
                        window.activeAudioPlayers = [];
                    }

                    // ✅ now switch dialogues
                    const currentId = this.getAttribute('data-current');
                    const nextId = this.getAttribute('data-next');

                    const currentDiv = document.getElementById('dialogue-' + currentId);
                    if (currentDiv) currentDiv.style.display = 'none';

                    if (nextId) {
                        const nextDiv = document.getElementById('dialogue-' + nextId);
                        if (nextDiv) nextDiv.style.display = 'flex';
                        startCamera();
                    } else {
                        Swal.fire('Well done!', 'You have completed all dialogues.', 'success');
                    }
                });
            });
        });
    </script>
@endsection

<style>
    .mt-2.segments-btn {
        display: flex;
        justify-content: space-between;
    }

    #blinking-button {
        padding: 8px 35px;
        border-radius: 48px 0px;
        border: 0px solid #6C8003;
    }

    @keyframes blink {

        0%,
        100% {
            background-color: #3F06FF;
        }

        50% {
            background-color: #8EDDBE;
        }
    }

    #blinking-button {
        background-color: #8EDDBE;
        color: white;
        animation: blink 2s linear infinite;
    }


    /* previus next button */
    button.btn.btn-secondary.prev-tab {
        background: #cfae00 !Important;
        border: none;
    }

    button.btn.btn-secondary.next-tab {
        background: #cfae00;
        border: none;
    }

    /* Visit Website  */
    #container {
        position: absolute;
        bottom: 0;
        width: 100%;
        background-color: #8EDDBE;
    }

    #inner-div {
        text-align: center;
        padding: 20px;
    }

    .col-lg-9.bg-camera {
        background: #fff;
        border-radius: 11px;
        padding: 17px;
    }

    .sidebar {
        background: #ffffff;
        padding: 18px;
        margin: 0px;
        border-radius: 14px;
        min-height: 633px;
    }

    p.desc {
        font-weight: 300;
        font-size: 17px;
    }

    .nav-tabs .nav-link.active,
    .nav-tabs .nav-item.show .nav-link {
        border-bottom: 1px solid #00000024 !important;
    }

    .diloce-title {
        background: #ffffff;
        padding: 11px;
        border-radius: 12px;
        margin-bottom: 12px;
    }

    button.btn.btn-primary {
        margin-bottom: 10px;
    }

    .diloce-title {
        background: #ffffff;
        padding: 11px;
        border-radius: 12px;
        margin-bottom: 12px;
        margin-left: -13px;
    }

    @media only screen and (max-width: 600px) {
        .sidebar {
            min-height: auto !important;
        }

        .col-lg-3.sides {
            padding: 1px;
            margin-top: 13px;
        }

    }
</style>


<script>
    document.querySelectorAll('.segment-tab').forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();

            const selectedId = this.getAttribute('data-id');

            // Hide all segment data
            document.querySelectorAll('.segment-data').forEach(content => {
                content.style.display = 'none';
            });

            // Show selected segment data
            const selectedSegment = document.getElementById('segment-data-' + selectedId);
            if (selectedSegment) {
                selectedSegment.style.display = 'block';
            }
        });
    });
</script>



@section('scripts')
    <script src="https://unpkg.com/wavesurfer.js@7/dist/wavesurfer.min.js"></script>
    <script src="https://unpkg.com/wavesurfer.js@7/dist/plugin/wavesurfer.microphone.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/wavesurfer.js"></script>


    <script>
        // camera close open
        const video = document.getElementById('camera-feed');
        const closeBtn = document.getElementById('close-camera');
        const cameraWrapper = document.getElementById('camera-wrapper');
        const reopenBtn = document.getElementById('reopen-camera');
        let stream;


        /* CAMERA CODE START */
        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: true
                });
                video.srcObject = stream;
                cameraWrapper.style.display = 'block';
                reopenBtn.style.display = 'none';
            } catch (error) {
                console.error('Camera access error:', error);
                alert('Camera access denied or not available.');
            }
        }

        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                video.srcObject = null;
            }
        }

        closeBtn.addEventListener('click', () => {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to turn off the camera?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, turn it off',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    stopCamera();
                    cameraWrapper.style.display = 'none';
                    reopenBtn.style.display = 'inline-block';
                }
            });
        });

        reopenBtn.addEventListener('click', startCamera);
        startCamera();
        /* CAMERA CODE END */




        /* TO VIEW ADMIN AUDIO CODE START */
        const wavesurfers = {};

        document.addEventListener('DOMContentLoaded', function() {
            // 1. Initialize waveform only for segments that exist in DOM
            document.querySelectorAll('.segment-container').forEach(container => {
                const segmentId = container.dataset.segmentId;
                const waveformEl = document.getElementById(`waveform-${segmentId}`);

                if (!waveformEl) return;

                // Init WaveSurfer
                wavesurfers[segmentId] = WaveSurfer.create({
                    container: `#waveform-${segmentId}`,
                    waveColor: '#9f9f9f',
                    progressColor: '#ff4500',
                    barWidth: 1,
                    barGap: 2,
                    height: 60,
                    cursorColor: '#ccc',
                    responsive: true,
                    hideScrollbar: true,
                    interact: false,
                });

                wavesurfers[segmentId].load(
                    `{{ asset('storage') }}/${container.dataset.segmentAudioPath || ''}`);

                wavesurfers[segmentId].on('ready', () => {
                    const duration = wavesurfers[segmentId].getDuration();
                    document.getElementById(`duration-${segmentId}`).innerText =
                        `${Math.round(duration)}s`;
                });

                const volInput = document.getElementById(`volume-${segmentId}`);
                if (volInput) {
                    volInput.oninput = () => {
                        wavesurfers[segmentId].setVolume(volInput.value);
                    };
                }

                const speedInput = document.getElementById(`speed-${segmentId}`);
                if (speedInput) {
                    speedInput.onchange = () => {
                        wavesurfers[segmentId].setPlaybackRate(speedInput.value);
                    };
                }
            });

            // 2. Start button plays audio first, then records
            document.querySelectorAll('.segment-container').forEach(container => {
                const segmentId = container.dataset.segmentId;
                let micStream, recorder, micWaveform, animationId, recordedBlob;

                const startBtn = container.querySelector(".startRecording");
                const stopBtn = container.querySelector(".stopRecording");
                // const playBtn = container.querySelector(".playRecording");
                // const playIcon = document.getElementById(`playIcon-${segmentId}`);
                const canvas = container.querySelector("canvas");
                const ctx = canvas.getContext("2d");

                const drawLiveWave = (analyser) => {
                    const bufferLength = analyser.fftSize;
                    const dataArray = new Uint8Array(bufferLength);

                    const draw = () => {
                        animationId = requestAnimationFrame(draw);
                        analyser.getByteTimeDomainData(dataArray);
                        ctx.fillStyle = "#f0f0f0";
                        ctx.fillRect(0, 0, canvas.width, canvas.height);
                        ctx.lineWidth = 2;
                        ctx.strokeStyle = "#4CAF50";
                        ctx.beginPath();

                        const sliceWidth = canvas.width / bufferLength;
                        let x = 0;

                        for (let i = 0; i < bufferLength; i++) {
                            const v = dataArray[i] / 128.0;
                            const y = v * canvas.height / 2;
                            if (i === 0) ctx.moveTo(x, y);
                            else ctx.lineTo(x, y);
                            x += sliceWidth;
                        }

                        ctx.lineTo(canvas.width, canvas.height / 2);
                        ctx.stroke();
                    };
                    draw();
                };

            startBtn.onclick = async () => {
                 // If already recorded once, warn before overwriting
                // If already recorded once, warn before overwriting
                if (recordedBlob) {
                    const result = await Swal.fire({
                        title: "Warning",
                        text: "Are you sure you want to repeat the segment?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes, Try Again",
                        cancelButtonText: "Cancel"
                    });

                    if (!result.isConfirmed) {
                        return; // stop here if cancelled
                    }

                    // disable next/prev again until new recording is complete
                    const nextBtn = container.querySelector(".next-tab");
                    if (nextBtn) nextBtn.disabled = true;

                    const prevBtn = container.querySelector(".prev-tab");
                    if (prevBtn) prevBtn.disabled = true;

                    // reset state
                    recordedBlob = null;
                    // playBtn.disabled = true;
                }

                // reset state
                recordedBlob = null;
                // playBtn.disabled = true;

                startBtn.disabled = true;
                startBtn.innerText = 'Playing segment...';

                const waveform = wavesurfers[segmentId];
                if (!waveform) return;

                waveform.play();

                waveform.once('finish', async () => {
                    startBtn.innerText = 'Record audio after beep...'; // Show beep phase
                    stopBtn.disabled = true; // Prevent stop during beep

                    // Play beep sound (5-second beep)
                    const beepSound = new Audio('/sounds/beep_2sec.mp3'); // Replace with your path if needed
                    window.activeAudioPlayers = window.activeAudioPlayers || [];
                    window.activeAudioPlayers.push(beepSound);
                    beepSound.play();


                    beepSound.onended = async () => {
                        // ✅ Now start actual recording
                        startBtn.innerText = 'Recording...';
                        stopBtn.disabled = false;

                        try {
                            micStream = await navigator.mediaDevices.getUserMedia({ audio: true });
                            
                            const audioCtx = new AudioContext();
                            const source = audioCtx.createMediaStreamSource(micStream);
                            const analyser = audioCtx.createAnalyser();
                            analyser.fftSize = 512;
                            source.connect(analyser);
                            drawLiveWave(analyser);

                            recorder = new MediaRecorder(micStream);
                            window.currentRecorder = recorder;
                            window.currentMicStream = micStream;

                            const chunks = [];

                            recorder.ondataavailable = e => chunks.push(e.data);

                            recorder.onstop = () => {
                                    cancelAnimationFrame(animationId);
                                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                                    // ✅ Create blob from chunks
                                    recordedBlob = new Blob(chunks, { type: 'audio/webm' });

                                    // ✅ Store blob for this segment
                                    window.responses = window.responses || {};
                                    window.responses[segmentId] = recordedBlob;

                                    // ✅ Update button state
                                    startBtn.innerText = 'Try Again';
                                    startBtn.disabled = false;
                                };

                            recorder.start();
                        } catch (err) {
                            alert("Microphone access error: " + err.message);
                            startBtn.disabled = false;
                            stopBtn.disabled = true;
                            startBtn.innerText = 'Start';
                        }
                    };
                });
                };


                stopBtn.onclick = () => {
                    stopBtn.disabled = true;
                    startBtn.disabled = false;
                    startBtn.innerText = 'Start';
                    if (recorder && recorder.state !== "inactive") recorder.stop();
                    if (micStream) micStream.getTracks().forEach(t => t.stop());
                      // ✅ Enable Next button for this segment
                    const nextBtn = container.querySelector(".next-tab");
                    if (nextBtn) nextBtn.disabled = false;
                    const prevBtn = container.querySelector(".prev-tab");
                    if (prevBtn) prevBtn.disabled = false;
                };

                // playBtn.onclick = () => {
                //     if (!micWaveform) return;
                //     micWaveform.playPause();
                //     const isPlaying = micWaveform.isPlaying();
                //     playIcon.classList.toggle('uil-play', !isPlaying);
                //     playIcon.classList.toggle('uil-pause', isPlaying);
                // };
                });
            });

        // TO SUBMITS THE DATA
        async function submitAllResponses() {
            const formData = new FormData();
            formData.append('mock_test_id', "{{ $practice->id }}");

            for (const [segmentId, blob] of Object.entries(window.responses || {})) {
                const dialogueContainer = document.querySelector(
                    `.segment-container[data-segment-id="${segmentId}"]`
                ).closest('.dialogue-container');

                const dialogueId = dialogueContainer.id.replace('dialogue-', '');

                const segmentElements = dialogueContainer.querySelectorAll('.segment-container');
                let segmentNumber = Array.from(segmentElements).findIndex(
                    el => el.dataset.segmentId === segmentId
                ) + 1;

                formData.append(
                    `responses[${dialogueId}_${segmentNumber}]`,
                    blob,
                    `dialogue${dialogueId}_segment${segmentNumber}.webm`
                );
            }

            try {
                const response = await fetch("{{ route('user.mocktest.submit') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: formData
                });

                const result = await response.json();

                Swal.fire('Success', result.message, 'success').then(() => {
                    window.location.href = "{{ route('user.MockTests.list') }}";
                });

            } catch (error) {
                Swal.fire('Error', error.message, 'error');
            }
        }


        document.addEventListener("DOMContentLoaded", () => {
            const progressEl = document.getElementById("segmentProgress");
            const allSegments = document.querySelectorAll('.tab-pane');
            let currentIndex = 1; // start at 1

            function updateProgress(newIndex) {
                progressEl.textContent = `${newIndex} / ${allSegments.length}`;
            }

            // Initially set progress
            updateProgress(currentIndex);
             // ✅ NEXT button → move + auto start
             document.querySelectorAll('.next-tab, .prev-tab').forEach(btn => {
                btn.disabled = true;
            });
            document.querySelectorAll('.next-tab').forEach(button => {
                button.addEventListener('click', function() {
                    const targetSelector = this.getAttribute('data-target');
                    const targetTab = document.querySelector(`a[href="${targetSelector}"]`);

                    if (targetTab) {
                        const tabInstance = new bootstrap.Tab(targetTab);
                        tabInstance.show();

                        const targetSegment = document.querySelector(targetSelector);
                        const startBtn = targetSegment.querySelector(".startRecording");

                        // ✅ check: recording exists if user-waveform has content
                        const userWaveform = targetSegment.querySelector(".user-recorded-waveform");
                        const hasRecording = userWaveform && userWaveform.children.length > 0;

                        if (!hasRecording && startBtn) {
                            startBtn.click(); // auto-start only if no recording yet
                        }

                        targetTab.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });
            document.querySelectorAll('.prev-tab').forEach(button => {
                button.addEventListener('click', function() {
                    const targetSelector = this.getAttribute('data-target');
                    const targetTab = document.querySelector(`a[href="${targetSelector}"]`);
                    

                    if (targetTab) {
                        const tabInstance = new bootstrap.Tab(targetTab);
                        tabInstance.show();
                        targetTab.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });

                        // find index of the new active segment
                        const newIndex = Array.from(allSegments).findIndex(
                            seg => seg.id === targetSelector.replace('#', '')
                        ) + 1;

                        if (newIndex > 0) {
                            currentIndex = newIndex;
                            updateProgress(currentIndex);
                        }
                    }
                });
            });
        });
         
        // code to update timer 
        document.addEventListener("DOMContentLoaded", function() {
            let duration = {{ $practice->duration * 60 }}; // in seconds (default 10 min if null)
            const countdownEl = document.getElementById("countdown");

            function updateTimer() {
                let minutes = Math.floor(duration / 60);
                let seconds = duration % 60;

                countdownEl.textContent =
                    String(minutes).padStart(2, '0') + ":" + String(seconds).padStart(2, '0');

                if (duration <= 0) {
                    clearInterval(timerInterval);
                    alert("⏰ Time is up! Submitting test automatically.");
                    submitAllResponses();
                }
                duration--;
            }

            updateTimer(); // initial
            let timerInterval = setInterval(updateTimer, 1000);
        });


        // SHOW ANSWER AS TEXT
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll('.show-answer').forEach(button => {
                button.addEventListener('click', function() {
                    const eng = this.getAttribute('data-answer-eng') || 'Not Available';
                    const hindi = this.getAttribute('data-answer-hindi') || 'Not Available';

                    document.getElementById('answerEng').textContent = eng;
                    document.getElementById('answerHindi').textContent = hindi;

                    const modal = new bootstrap.Modal(document.getElementById('answerModal'));
                    modal.show();
                });
            });
        });
    </script>
    {{-- notes --}}
    <script>
        // document.getElementById('takeNoteBtn').addEventListener('click', function () {
        function takeNoteBtn() {
            // Remove if already exists
            const existingModal = document.getElementById('takeNotesModal');
            if (existingModal) existingModal.remove();

            // Create modal HTML dynamically
            const modalHtml = `
                <div class="modal fade" id="takeNotesModal" tabindex="-1" aria-labelledby="takeNotesLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="noteForm">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="takeNotesLabel">Take Your Notes</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <textarea name="note" id="note" class="form-control" rows="4" placeholder="Write your note here..."></textarea>
                                    <div id="noteError" class="text-danger mt-2" style="display:none;"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Save Note</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHtml);

            const modal = new bootstrap.Modal(document.getElementById('takeNotesModal'));
            modal.show();

            // Fetch and preload existing note
            fetch("{{ route('notes.get', ['practice' => $practice->id]) }}")
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.note) {
                        document.getElementById('note').value = data.note;
                    }
                });

            // Handle form submission
            document.getElementById('noteForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const noteContent = document.getElementById('note').value;

                fetch("{{ route('notes.update', ['practice' => $practice->id]) }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            note: noteContent,
                            practice_id: {{ $practice->id }}
                        })
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Network error');
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            modal.hide();
                            Swal.fire({
                                title: 'Saved!',
                                text: data.message,
                                icon: 'success',
                                timer: 2000
                            });
                        } else {
                            document.getElementById('noteError').innerText = data.message ||
                                "Something went wrong";
                            document.getElementById('noteError').style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('noteError').innerText = "Error saving note.";
                        document.getElementById('noteError').style.display = 'block';
                    });
            });
        }

        //         document.addEventListener('DOMContentLoaded', function() {
        //     const segmentIndexElements = document.querySelectorAll('.segment-index');

        //     function updateSegmentIndex(index) {
        //         segmentIndexElements.forEach(el => el.textContent = index);
        //     }

        //     // Handle tab click
        //     document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
        //         tab.addEventListener('shown.bs.tab', function(e) {
        //             const index = e.target.getAttribute('data-segment-index');
        //             updateSegmentIndex(index);
        //         });
        //     });

        //     // Handle page load: find active tab and set index
        //     const activeTab = document.querySelector('[data-bs-toggle="tab"].active');
        //     if (activeTab) {
        //         const initialIndex = activeTab.getAttribute('data-segment-index');
        //         updateSegmentIndex(initialIndex);
        //     }
        // });
        // });
    </script>

    {{-- notes --}}
    @vite(['resources/js/pages/dashboard.js'])
@endsection
