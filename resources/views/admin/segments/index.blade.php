{{-- @foreach ($dialogues as $dialogue)
    <h4>{{ $dialogue->title }}</h4>
    <audio controls src="{{ asset('storage/' . $dialogue->audio_path) }}"></audio>

    <form action="{{ route('dialogues.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="dialogue_id" value="{{ $dialogue->id }}">
        <input type="file" name="audio" accept="audio/*" required>
        <button type="submit">Upload Response</button>
    </form>
@endforeach --}}


{{-- <audio controls>
    <source src="{{ asset('storage/audios/kinni_kinni.mp3') }}" type="audio/mpeg">
    Your browser does not support the audio element.
</audio>

<audio controls>
    <source src="{{ asset('storage/audios/tone-test.mp3') }}" type="audio/mpeg">
    Your browser does not support the audio element.
</audio> --}}



@extends('layouts.vertical', ['title' => 'Dashboard', 'topbarTitle' => 'Dashboard'])

@section('content')
    <div>
        NAATI CCL Portal
        <div>
            ``{{ $practice->description }}

        </div>
    </div>


    <div class="row">

        {{-- camera and waveform  in this whole div --}}
        <div class="col-lg-6">




            {{-- camera --}}
            <div id="camera-wrapper"
                style="position: relative; width: 100%; max-width: 250px; height: 250px; border: 2px solid #ccc; border-radius: 12px; overflow: hidden;">
                <!-- Close Button -->
                <button id="close-camera"
                    style="
                     position: absolute;
                     top: 10px;
                     left: 10px;
                     z-index: 10;
                     background: rgba(0,0,0,0.5);
                     color: white;
                     border: none;
                     border-radius: 50%;
                     width: 30px;
                     height: 30px;
                        cursor: pointer;
                                    font-size: 18px;
                        line-height: 28px;
                     text-align: center;
              ">×</button>

                <!-- Video Feed -->
                <video id="camera-feed" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover;"></video>
            </div>

            <!-- Reopen Camera Button -->
            <button id="reopen-camera" class="btn btn-primary mt-3" style="display: none;">
                Turn On Camera
            </button>
            {{-- camera --}}





            {{-- author test recording --}}
            <div class="bg-[#818a91]">
                {{-- test segment --}}

                <div id="waveform-{{ $segment->id }}" class="mt-4 bg-" style="width: 100%; height: 80px;"></div>

                <div class="d-flex justify-content-between align-items-center mt-2">
                    <div>
                        {{-- Volume Control --}}
                        <label for="volume-{{ $segment->id }}" class="me-1 text-muted small">🔊</label>
                        <input type="range" min="0" max="1" step="0.01" value="1"
                            id="volume-{{ $segment->id }}" style="width: 80px; vertical-align: middle;" />

                        {{-- Speed Control --}}
                        <label for="speed-{{ $segment->id }}" class="ms-3 me-1 text-muted small">⏩</label>
                        <select id="speed-{{ $segment->id }}" style="width: 70px; font-size: 0.8rem;">
                            <option value="0.75">0.75x</option>
                            <option value="1" selected>1x</option>
                            <option value="1.25">1.25x</option>
                            <option value="1.5">1.5x</option>
                            <option value="2">2x</option>
                        </select>

                        {{-- Play Button --}}
                        <button onclick="wavesurfers[{{ $segment->id }}].playPause()"
                            class="btn btn-sm btn-outline-primary ms-3">
                            ▶️ Play / Pause
                        </button>
                    </div>

                    {{-- Duration --}}
                    <div id="duration-{{ $segment->id }}" class="text-muted small"></div>
                </div>

              
{{die;}}
                <div class="mt-4">
                    <h5>Record Your Responsessssssssfsdfsdfdfsdfsdfsssssssssssssssssssssss</h5>

                    {{-- Waveform for recorded audio --}}
                    <div id="user-recorded-waveform" style="width: 100%; height: 100px;"></div>

                    {{-- Recording Controls --}}
                    <div class="mt-2 d-flex gap-2 align-items-center">
                        <button type="button" id="startRecording" class="btn btn-danger btn-sm">🎙️ Start</button>
                        <button type="button" id="stopRecording" class="btn btn-dark btn-sm" disabled>⏹️ Stop</button>
                        <button type="button" id="playRecording" class="btn btn-secondary btn-sm" disabled>▶️ Play</button>
                    </div>

                    {{-- Form to submit audio --}}
                    <form id="uploadForm" action="" method="POST"
                        enctype="multipart/form-data" class="mt-3">
                        @csrf
                        <input type="hidden" name="segment_id" value="{{ $segment->id }}">
                        <input type="hidden" name="audio_blob" id="audioBlob">
                        <button type="submit" class="btn btn-outline-success btn-sm" disabled id="submitRecording">Submit
                            Response</button>
                    </form>
                </div>

            </div>











        </div>
<!-- Vertically centered modal -->

        <div class="col-lg-3 col-sm-12 ccl-test">
            <h4 class=" fw-bolder" style="color: rgb(193, 150, 23);">Hindi CCL TESTii</h4>
            <p>To begin the segment:</p>
            <ul>
                <li> Click<button type="button"
                        class="me-2 ms-2 btn btn-danger fs-4 btn-sm d-inline-flex align-items-center justify-content-center"
                        style="cursor: none;">Start</button>Please speak after the beep sound</li>
            </ul>
            <p>After you have finished speaking:</p>
            <ul>
                <li> Click<button type="button"
                        class="btn btn-outline-dark me-2 ms-2 fs-4 btn-sm d-inline-flex align-items-center justify-content-center"
                        style="cursor: none;">Finish attempt</button>to save the recording</li>
            </ul>
            <div class="mt-3 mb-2"><span class="me-2">•</span> Click<button
                    class="btn btn-outline-success btn-sm rounded-pill ms-2 me-2 d-inline-flex" style="cursor: none;">Next
                    <i class="ti ti-player-track-next fs-5 ms-2"></i></button>to move on to the next segment</div>
            <h4 class="fw-bolder" style="cursor: none; color: rgb(193, 150, 23);">Repeat:</h4>
            <div class="mt-2 mb-2"><span class="">
                    <p>To Repeat the segment click on the Finish Attempt and click the</p>
                </span><button type="button"
                    class="btn me-2 ms-2 btn-danger fs-4 btn-sm d-inline-flex align-items-center justify-content-center"
                    style="cursor: none;">Start</button>button again.</div>
            <p>(with one repeat allowed per dialogue and no penalty).</p>
        </div>
        <div class="col-lg-3">
            <ul class="overflow-auto invoice-users" style="height: calc(0px + 75vh);">

                @foreach ($practice->segments as $index => $seg)
                    <li style="cursor: pointer;">
                        <a class="p-3 bg-hover-light-black border-bottom d-flex align-items-start invoice-user listing-user"
                            href="{{ route('admin.segments.index', ['practice' => $practice->id, 'segment_id' => $seg->id]) }}">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="ti ti-microphone fs-6" style="color: rgb(193, 150, 23);"></i>
                            </div>
                            <div class="ms-3 d-inline-block w-75">
                                <h6 data-seg="{{ $seg->id }}" class="mb-0 invoice-customer">
                                    Segment {{ $index + 1 }}
                                </h6>
                            </div>
                        </a>
                    </li>
                @endforeach


            </ul>
        </div>







    </div>
    <div>

    </div>


   
@endsection

@section('scripts')
@section('scripts')
    <script src="https://unpkg.com/wavesurfer.js@7/dist/plugin/wavesurfer.microphone.min.js"></script>
    <script src="https://unpkg.com/wavesurfer.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/wavesurfer.js"></script>


    <script>
        const video = document.getElementById('camera-feed');
        const closeBtn = document.getElementById('close-camera');
        const cameraWrapper = document.getElementById('camera-wrapper');
        const reopenBtn = document.getElementById('reopen-camera');
        let stream;

        // Start camera
        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: true,
                    audio: false
                });
                video.srcObject = stream;
                cameraWrapper.style.display = 'block';
                reopenBtn.style.display = 'none';
            } catch (error) {
                console.error('Camera access error:', error);
                alert('Camera access denied or not available.');
            }
        }

        // Stop camera
        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                video.srcObject = null;
            }
        }

        // Handle close button
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

        // Reopen camera
        reopenBtn.addEventListener('click', () => {
            startCamera();
        });

        // Auto-start camera
        startCamera();



        // waveform

        const wavesurfers = {};


        wavesurfers[{{ $segment->id }}] = WaveSurfer.create({
            container: '#waveform-{{ $segment->id }}',
            waveColor: '#9f9f9f', // soft grey
            progressColor: '#ff4500', // darker for progress
            barWidth: 1, // thin bars
            barGap: 2,
            height: 60,
            cursorColor: '#ccc', // optional: red progress line
            responsive: true,
            hideScrollbar: true,
        });


        wavesurfers[{{ $segment->id }}].load("{{ asset('storage/' . $segment->segment_path) }}");
        wavesurfers[{{ $segment->id }}].on('ready', function() {
            const duration = wavesurfers[{{ $segment->id }}].getDuration();
            document.getElementById('duration-{{ $segment->id }}').innerText = `${Math.round(duration)}s`;
        });

      
    let recorder;
    let recordedBlob;
    let userWaveform;

    document.addEventListener("DOMContentLoaded", () => {
        const startBtn = document.getElementById("startRecording");
        const stopBtn = document.getElementById("stopRecording");
        const playBtn = document.getElementById("playRecording");
        const submitBtn = document.getElementById("submitRecording");
        const audioInput = document.getElementById("audioBlob");

        // Init WaveSurfer
        userWaveform = WaveSurfer.create({
            container: '#user-recorded-waveform',
            waveColor: '#a0d2eb',
            progressColor: '#10c469',
            height: 80,
             barWidth: 1, // thin bars
            barGap: 2,
             plugins: [
       
    ]
            
        });

        startBtn.onclick = async () => {
            if (navigator.mediaDevices.getUserMedia) {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                recorder = new MediaRecorder(stream);

                const chunks = [];
                recorder.ondataavailable = e => chunks.push(e.data);
                recorder.onstop = () => {
                    recordedBlob = new Blob(chunks, { type: 'audio/webm' });
                    const audioURL = URL.createObjectURL(recordedBlob);
                    userWaveform.load(audioURL);
                    playBtn.disabled = false;
                    submitBtn.disabled = false;

                    // Set blob as base64 for form
                    const reader = new FileReader();
                    reader.onloadend = () => {
                        audioInput.value = reader.result;
                    };
                    reader.readAsDataURL(recordedBlob);
                };

                recorder.start();
                startBtn.disabled = true;
                stopBtn.disabled = false;
            } else {
                alert("Microphone access not supported.");
            }
        };

        stopBtn.onclick = () => {
            recorder.stop();
            stopBtn.disabled = true;
            startBtn.disabled = false;
        };

        playBtn.onclick = () => {
            userWaveform.playPause();
        };
    });

    </script>



   

    @vite(['resources/js/pages/dashboard.js'])
@endsection

@vite(['resources/js/pages/dashboard.js'])
@endsection
