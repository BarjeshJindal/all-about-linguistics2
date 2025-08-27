@extends('layouts.vertical', ['title' => 'Vip Exam Material ', 'topbarTitle' => 'Vip Exam Material'])

@section('content')
    <link href="https://unicons.iconscout.com/release/v4.0.8/css/line.css" rel="stylesheet">

    <!-- Button trigger modal -->
    <div class="inst">
        <button type="button" class="btn btn-primary" id="blinking-button" data-bs-toggle="modal"
            data-bs-target="#exampleModal">
            Instructions
        </button>
    </div>

    <div class="diloce-title">
        <h2>Dialogue - {{ $dialogue->title }}</h2>
        <p class="desc">{{ $dialogue->description }}</p>
        <input type="hidden" id="dialogueIdInput" value="{{ $dialogue->id }}">

        <div class="d-flex flex-wrap align-items-center gap-3">


            <i class="ti ti-clock fs-5 me-2"></i> <span id="page-timer" class="badge bg-danger py-2 px-3"></span>

            <span class="segment-index">{{ $segments->first()->id ?? '' }}</span>
            <span><span class="segment-index">{{ $segments->first()->id ?? '' }}</span>/{{ count($segments) }}</span>

            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-tag me-2 text-{{ $userLabel->color ?? 'secondary' }}"></i>
                    {{ $userLabel->name ?? 'Add Label' }}
                </button>
                <ul class="dropdown-menu">
                    @foreach ($labels as $label)
                        <li>
                            <button type="submit" class="dropdown-item d-flex  align-items-center select-label"
                                data-dialogue-id="{{ $dialogue->id }}" data-label-id="{{ $label->id }}"
                                data-label-name="{{ $label->name }}" data-label-color="{{ $label->color }}">
                                <i class="ti ti-tag me-2 text-{{ $label->color }}"></i>
                                {{ $label->name }}
                            </button>
                        </li>
                    @endforeach
                </ul>

            </div>
        </div>

        <!-- Modal -->
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
                            <li>
                                Click the <button class="startRecording btn btn-danger">Start Recording</button> button.
                            </li>
                            <li>
                                Once you are done speaking, click the <button
                                    class="stopRecording btn btn-danger">Stop</button>
                                button.
                            </li>
                            <li>
                                To listen to your recorded audio, click the <button
                                    class="playRecording btn btn-success">Play</button> button.
                                <br>🔊 This will replay what you just recorded.
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs on Right Side -->
        <div class="row">
            <!-- Segment Content (Tabs) -->
            <div class="col-lg-9 bg-camera">

                {{-- camera section --}}
                <div id="camera-wrapper"
                    style="position: relative; width: 100%; max-width: 250px; height: 250px; border: 2px solid #ccc; border-radius: 12px; overflow: hidden;">
                    <button id="close-camera"
                        style="position: absolute; top: 10px; left: 10px; z-index: 10; background: rgba(0,0,0,0.5); color: white; border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer; font-size: 18px; line-height: 28px; text-align: center;">×</button>
                    <video id="camera-feed" autoplay playsinline
                        style="width: 100%; height: 100%; object-fit: cover;"></video>
                </div>
                <button id="reopen-camera" class="btn btn-primary mt-3" style="display: none;">Turn On Camera</button>
                {{-- test section  --}}
                <div class="tab-content" id="segmentTabContent">
                    @foreach ($segments as $segment)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                            id="segment-{{ $segment->id }}">
                            <!-- Audio Player -->
                            <div class="bg-[#818a91] mt-4">
                                <div id="waveform-{{ $segment->id }}" style="width: 100%; height: 80px;"></div>

                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div>
                                        <label class="me-1 text-muted small">🔊</label>
                                        <input type="range" min="0" max="1" step="0.01" value="1"
                                            id="volume-{{ $segment->id }}" style="width: 80px;" />

                                        <label class="ms-3 me-1 text-muted small">⏩</label>
                                        <select id="speed-{{ $segment->id }}" style="width: 70px;">
                                            <option value="0.75">0.75x</option>
                                            <option value="1" selected>1x</option>
                                            <option value="1.25">1.25x</option>
                                            <option value="1.5">1.5x</option>
                                            <option value="2">2x</option>
                                        </select>

                                        {{-- <button onclick="wavesurfers[{{ $segment->id }}].playPause()" class="btn btn-sm btn-outline-primary ms-3">▶️ Play / Pause</button> --}}
                                    </div>
                                    <div id="duration-{{ $segment->id }}" class="text-muted small"></div>


                                </div>

                                <!-- Recording Section -->
                                <div class="segment-container" data-segment-id="{{ $segment->id }}">
                                    {{-- <h5>Record Your Response</h5> --}}

                                    <canvas width="400" height="100"></canvas>
                                    <div id="user-waveform-{{ $segment->id }}" class="user-recorded-waveform"></div>
                                    <button id="playPauseBtn"
                                        class="playRecording bg-white border border-primary text-primary p-1 rounded-full shadow hover:bg-primary hover:text-white transition disabled:opacity-50"
                                        disabled>
                                        <i class="uil uil-play" id="playIcon"></i>
                                    </button>
                                    {{-- notes --}}
                                    {{-- <button type="button" id="" onclick="takeNoteBtn()"
                                        class="p-2 btn btn-light-success text-success btn-sm rounded-pill me-2 d-inline-flex align-items-center justify-content-center fa-pull-right"
                                        style="font-weight: bold;">
                                        <i class="ti ti-edit fs-5 me-2"></i>Take Your Notes
                                    </button> --}}
                                    {{-- notes --}}

                                    <div>
                                        <span class="user-recorded-duration text-muted small"></span>
                                    </div>

                                    <div class="mt-2 d-flex segments-btn">
                                        <div class="start-stop-btn">
                                            <button class="startRecording btn btn-danger">Start Test </button>
                                            <button class="stopRecording btn btn-danger" disabled>Stop</button>
                                        </div>

                                        <!-- Answer Modal -->

                                        <button class="btn btn-info p-2 btn-sm show-answer "
                                            data-answer-eng="{{ $segment->answer_eng }}"
                                            data-answer-hindi="{{ $segment->answer_other_language }}">
                                            Answer
                                        </button>




                                        <!-- Answer Modal -->


                                    </div>

                                    <div class="d-flex justify-content-between mt-4">


                                        @if (!$loop->first)
                                            <button class="btn btn-secondary prev-tab"
                                                data-target="#segment-{{ $segments[$loop->index - 1]->id }}">
                                                ⬅️ Previous
                                            </button>
                                        @endif

                                        @if (!$loop->last)
                                            <button class="btn btn-secondary next-tab"
                                                data-target="#segment-{{ $segments[$loop->index + 1]->id }}">
                                                Next ➡️
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                {{-- end test section --}}
            </div>
            <div class="modal fade" id="answerModal" tabindex="-1" aria-labelledby="answerModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="answerModalLabel">Answer Preview</h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h5 class="text-primary fw-bold">English Answer:</h5>
                            <p id="answerEng" class="mb-3 text-dark"></p>

                            <h5 class="text-success fw-bold">{{-- $practice->second_language --}} Answer:</h5>
                            <p id="answerHindi" class="mb-0 text-dark"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Segment List (Tabs Nav) -->
            <div class="col-lg-3 sides">
                <div class="sidebar">
                    <ul class="nav flex-column nav-tabs" id="segmentTab" role="tablist">
                        @foreach ($segments as $segment)
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-start {{ $loop->first ? 'active' : '' }}"
                                    id="segment-tab-{{ $segment->id }}" data-bs-toggle="tab"
                                    data-segment-index="{{ $loop->iteration }}" href="#segment-{{ $segment->id }}"
                                    role="tab">
                                    <i class="ti ti-microphone me-2" style="color: rgb(193, 150, 23);"></i>
                                    Segment {{ $loop->iteration }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

      <button type="button" onclick="submitAllResponses()" class="btn btn-success mt-4">
            Submit Test
    </button>
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

            //  segments waveform
            const wavesurfers = {};

            @foreach ($segments as $segment)
                wavesurfers[{{ $segment->id }}] = WaveSurfer.create({
                    container: '#waveform-{{ $segment->id }}',
                    waveColor: '#9f9f9f',
                    progressColor: '#ff4500',
                    barWidth: 1,
                    barGap: 2,
                    height: 60,
                    cursorColor: '#ccc',
                    responsive: true,
                    hideScrollbar: true,
                });

                wavesurfers[{{ $segment->id }}].load("{{ asset('storage/' . $segment->segment_path) }}");

                wavesurfers[{{ $segment->id }}].on('ready', () => {
                    const duration = wavesurfers[{{ $segment->id }}].getDuration();
                    document.getElementById('duration-{{ $segment->id }}').innerText = `${Math.round(duration)}s`;
                });

                document.getElementById('volume-{{ $segment->id }}').oninput = function() {
                    wavesurfers[{{ $segment->id }}].setVolume(this.value);
                };

                document.getElementById('speed-{{ $segment->id }}').onchange = function() {
                    wavesurfers[{{ $segment->id }}].setPlaybackRate(this.value);
                };
            @endforeach

            document.addEventListener("DOMContentLoaded", () => {
                const responses = {};

                document.querySelectorAll(".segment-container").forEach(container => {
                    const segmentId = container.dataset.segmentId;
                    let micStream, recorder, micWaveform, animationId, recordedBlob;

                    const startBtn = container.querySelector(".startRecording");
                    const stopBtn = container.querySelector(".stopRecording");
                    const playBtn = container.querySelector(".playRecording");
                    const playIcon = document.getElementById('playIcon');
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

                                if (i === 0) {
                                    ctx.moveTo(x, y);
                                } else {
                                    ctx.lineTo(x, y);
                                }
                                x += sliceWidth;
                            }

                            ctx.lineTo(canvas.width, canvas.height / 2);
                            ctx.stroke();
                        };

                        draw();
                    };
                    startBtn.onclick = async () => {
                        startBtn.disabled = true;
                        startBtn.innerText = 'Playing segment...'; // Step 1 label
                        
                        const waveform = wavesurfers[segmentId];
                        waveform.play();
                       

                        waveform.once('finish', async () => {
                            startBtn.innerText = 'Record audio after beep...'; // Indicate beep phase
                            stopBtn.disabled = true; // Disable stop until beep finishes

                            const beepSound = new Audio('/sounds/beep_5s.wav'); // Path to your file
                            beepSound.play();

                            beepSound.onended = async () => {
                                // ✅ After beep ends, start recording
                                startBtn.innerText = 'Recording...'; // Now it's actually recording
                                stopBtn.disabled = false; // Enable stop now

                                try {
                                micStream = await navigator.mediaDevices.getUserMedia({
                                    audio: true
                                });
                              

                                const audioCtx = new AudioContext();
                                const source = audioCtx.createMediaStreamSource(micStream);
                                const analyser = audioCtx.createAnalyser();
                                analyser.fftSize = 512;
                                source.connect(analyser);
                                drawLiveWave(analyser);

                                recorder = new MediaRecorder(micStream);
                                const chunks = [];

                                recorder.ondataavailable = e => chunks.push(e.data);

                                recorder.onstop = () => {
                                    cancelAnimationFrame(animationId);
                                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                                    recordedBlob = new Blob(chunks, {
                                        type: 'audio/webm'
                                    });

                                    const audioURL = URL.createObjectURL(recordedBlob);

                                    if (micWaveform) micWaveform.destroy();

                                    micWaveform = WaveSurfer.create({
                                        container: container.querySelector(
                                            `#user-waveform-${segmentId}`),
                                        waveColor: '#a0d2eb',
                                        progressColor: '#10c469',
                                        height: 80,
                                        barWidth: 1,
                                        barGap: 2,
                                    });

                                    micWaveform.load(audioURL);
                                    micWaveform.on('ready', () => {
                                        const duration = micWaveform
                                            .getDuration();
                                        container.querySelector(
                                                '.user-recorded-duration')
                                            .innerText =
                                            `${Math.round(duration)}s`;
                                    });

                                    responses[segmentId] = recordedBlob;
                                    playBtn.disabled = false;

                                    // Reset button text
                                    startBtn.innerText = 'Try Again ';
                                };

                                recorder.start();
                            } catch (err) {
                                alert("Microphone access error: " + err.message);
                                startBtn.disabled = false;
                                stopBtn.disabled = true;
                                startBtn.innerText = 'Start';
                            }
                        } 
                        });
                    };

                    stopBtn.onclick = () => {
                        stopBtn.disabled = true;
                        startBtn.disabled = false;
                        startBtn.innerText = 'Start'; // reset label

                        if (recorder && recorder.state !== "inactive") recorder.stop();
                        if (micStream) micStream.getTracks().forEach(t => t.stop());
                    };

                    playBtn.onclick = () => {
                        if (!micWaveform) return;

                        micWaveform.playPause();

                        const isPlaying = micWaveform.isPlaying();

                        playIcon.classList.toggle('uil-play', !isPlaying);
                        playIcon.classList.toggle('uil-pause', isPlaying);
                    };

                });

                window.responses = responses;

            });


            async function submitAllResponses() {
                    const formData = new FormData();
                    formData.append('dialogue_id', document.getElementById('dialogueIdInput').value);

                    for (let segmentId in responses) {
                        const file = new File([responses[segmentId]], `segment_${segmentId}.webm`, { type: "audio/webm" });
                        formData.append('responses[]', file);
                        formData.append('segment_ids[]', segmentId);
                    }

                    try {
                        const response = await fetch("{{ route('user.vip-exam-segments.storeAll') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            },
                            body: formData,
                        });

                        if (!response.ok) {
                            // Try to parse JSON error
                            const errorData = await response.json().catch(() => null);

                            if (errorData && errorData.error) {
                                Swal.fire('Error', errorData.error, 'error');
                            } else {
                                const text = await response.text();
                                console.error("Server error:", text);
                                Swal.fire('Error', 'Server returned an error. Check console.', 'error');
                            }
                            return;
                        }

                        const result = await response.json();
                        Swal.fire('Success', result.message, 'success').then(() => {
                            window.location.href = result.redirect;
                        });

                    } catch (err) {
                        console.error("JS fetch error:", err);
                        Swal.fire('Error', 'Could not submit responses.', 'error');
                    }

            }


            document.addEventListener("DOMContentLoaded", () => {
                document.querySelectorAll('.next-tab, .prev-tab').forEach(button => {
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
                        }
                    });
                });
            });


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
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.select-label').forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.preventDefault();

                        const dialogueId = this.dataset.dialogueId;
                        const labelId = this.dataset.labelId;
                        const labelName = this.dataset.labelName;
                        const labelColor = this.dataset.labelColor; // ✅ You missed this line

                        const button = this.closest('.dropdown').querySelector('button');
                        button.innerHTML =
                            `<i class="ti ti-tag me-2 text-${labelColor}"></i> ${labelName}`;

                        fetch(`/label/${dialogueId}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({
                                    label_id: labelId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    button.innerHTML =
                                        `<i class="ti ti-tag me-2 text-${labelColor}"></i> ${labelName}`;
                                } else {
                                    alert(data.message || 'Failed to update label.');
                                }
                            });
                    });
                });
            });
        </script>


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let elapsed = 0;
                const timerDisplay = document.getElementById("page-timer");

                function startCountUp() {
                    setInterval(() => {
                        const minutes = Math.floor(elapsed / 60);
                        const secs = elapsed % 60;
                        timerDisplay.textContent =
                            `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                        elapsed++;
                    }, 1000);
                }

                startCountUp();
            });
            document.addEventListener('DOMContentLoaded', function() {
                const segmentIndexElements = document.querySelectorAll('.segment-index');

                function updateSegmentIndex(index) {
                    segmentIndexElements.forEach(el => el.textContent = index);
                }

                // Handle tab click
                document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
                    tab.addEventListener('shown.bs.tab', function(e) {
                        const index = e.target.getAttribute('data-segment-index');
                        updateSegmentIndex(index);
                    });
                });

                // Handle page load: find active tab and set index
                const activeTab = document.querySelector('[data-bs-toggle="tab"].active');
                if (activeTab) {
                    const initialIndex = activeTab.getAttribute('data-segment-index');
                    updateSegmentIndex(initialIndex);
                }
            });
        </script>

        {{-- notes --}}
        @vite(['resources/js/pages/dashboard.js'])
    @endsection
