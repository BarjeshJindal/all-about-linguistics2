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
                      const playBeep = (duration = 500, frequency = 800, volume = 1) => {
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioCtx.createOscillator();
        const gainNode = audioCtx.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioCtx.destination);

        oscillator.type = 'sine';
        oscillator.frequency.value = frequency;
        gainNode.gain.value = volume;

        oscillator.start();
        oscillator.stop(audioCtx.currentTime + duration / 1000);
    };
                    const waveform = wavesurfers[segmentId];
                    waveform.play();

                    waveform.once('finish', async () => {
                        startBtn.innerText = 'Recording...'; // Step 2 label
                        stopBtn.disabled = false;

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
                    if (micWaveform) micWaveform.playPause();
                };

            });

            window.responses = responses;

        });


        async function submitAllResponses() {
            const formData = new FormData();

            for (let segmentId in responses) {
                formData.append('responses[]', responses[segmentId], `segment_${segmentId}.webm`);
                formData.append('segment_ids[]', segmentId);
            }

            for (let pair of formData.entries()) {
                console.log(pair[0] + ':', pair[1]);
            }

            const response = await fetch("{{ route('user.segments.storeAll') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                body: formData,
            });

            const result = await response.json();
            Swal.fire('Success', result.message, 'success');
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