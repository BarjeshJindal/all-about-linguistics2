@extends('layouts.vertical', ['title' => 'Create Mock Test', 'topbarTitle' => 'Create Mock Test'])

@section('content')
    <div class="card shadow-sm border-0">
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.mock-tests.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Mock Test Info --}}
            <div class="mb-3">
                <label class="form-label">Mock Test Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mock Test Duration (in minutes)</label>
                <input type="number" name="duration" class="form-control" min="1" placeholder="Enter duration in minutes" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Second Language</label>
                <select id="language" name="language_id" class="form-control" required>
                    <option value="">Select second language</option>
                    @foreach ($languages as $language)
                        <option value="{{ $language->id }}">{{ ucfirst($language->second_language) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- DIALOGUES (2 blocks) --}}
            <div id="dialogues-container">
                @for ($d = 0; $d < 2; $d++)
                    <div class="dialogue-block border p-2 p-md-4 mb-4 rounded-2xl bg-light rounded-2">
                        <h3 class="mb-3">Dialogue {{ $d + 1 }}</h3>

                        <div class="mb-3">
                            <label class="form-label">Dialogue Title</label>
                            <input type="text" name="dialogues[{{ $d }}][title]" class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="dialogues[0][description]" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Translation Flow</label>
                            <select name="dialogues[{{ $d }}][translation_flow]" class="form-control" required>
                                <option value="">-- Select --</option>
                                <option value="english_to_other">English → Other Language</option>
                                <option value="other_to_english">Other Language → English</option>
                            </select>
                        </div>


                        {{-- SEGMENT WRAPPER --}}
                        <div class="segment-wrapper" data-dialogue-index="{{ $d }}">
                            {{-- Segment 1 by default --}}
                            <div class="segment-block border p-3 mb-3 rounded bg-white">
                                <h4 class="segment-title mb-3">Segment 1</h4>
                                <label class="form-label">Audio File(MP3)</label>
                                <input type="file" name="dialogues[{{ $d }}][segments][0][segment_path]"
                                    class="form-control" required>

                                {{-- NEW FIELD: Sample Response --}}
                                <div class="mt-3">
                                    <label class="form-label">Sample Response (MP3)</label>
                                    <input type="file" name="dialogues[{{ $d }}][segments][0][sample_response]"
                                        class="form-control">
                                </div>

                                <div class="row mt-2">
                                    <div class="col-sm-6">
                                        <label class="form-label">Answer (English)</label>
                                        <input type="text" name="dialogues[{{ $d }}][segments][0][answer_eng]"
                                            class="form-control" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label answer-languages-label">Answer Second Language</label>
                                        <input type="text"
                                            name="dialogues[{{ $d }}][segments][0][answer_second_language]"
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-sm btn-success add-segment-btn"
                            data-dialogue-index="{{ $d }}">+ Add Segment</button>
                    </div>
                @endfor
            </div>

            <button type="submit" class="btn btn-success submit">Submit Mock Test</button>
        </form>
    </div>
    </div>

    <style>
        button.submit {
            background: #e1c21e;
            border-color: #e1c21e;
        }

        .add-segment-btn {
            background: #10c469;
            border-color: #10c469;
            margin-top: 10px;
        }
    </style>

    <script>

        let segmentIndex = 1;

        document.addEventListener('DOMContentLoaded', function() {
            const languageSelect = document.getElementById('language');

            if (languageSelect) {
                languageSelect.addEventListener('change', function() {
                    const selectedLang = this.options[this.selectedIndex].text || 'Second Language';
                    document.querySelectorAll('.answer-languages-label').forEach(label => {
                        label.textContent = `Answer ${capitalizeFirstLetter(selectedLang)} `;
                    });
                });
            }

            // Capitalize helper
            function capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }

            document.querySelectorAll('.add-segment-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const dIndex = this.dataset.dialogueIndex;
                    const wrapper = document.querySelector(
                        `.segment-wrapper[data-dialogue-index="${dIndex}"]`
                    );

                    const segmentCount = wrapper.querySelectorAll('.segment-block').length;
                    const newIndex = segmentCount + 1; // local index for this dialogue

                    const languageSelect2 = document.getElementById('language');
                    let selectedLang = "Second Language";
                    if (languageSelect2 && languageSelect2.selectedIndex > 0) {
                        selectedLang = languageSelect2.options[languageSelect2.selectedIndex].text || "Second Language";
                    }

                    // ✅ Max 6 segments
                    if (segmentCount >= 6) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Limit Reached',
                            text: 'You can only add up to 6 segments.',
                            confirmButtonText: 'OK'
                        });                
                        return;
                    }

                   const html = `<div class="segment-block border p-3 mb-3 rounded bg-white">
                            <h4 class="segment-title mb-3">Segment ${newIndex}</h4>   

                            <label class="form-label">Audio File (MP3)</label>
                            <input type="file" name="dialogues[${dIndex}][segments][${segmentCount}][segment_path]" 
                                class="form-control" required>

                            <!-- NEW FIELD: Sample Response -->
                            <div class="mt-3">
                                <label class="form-label">Sample Response (MP3)</label>
                                <input type="file" name="dialogues[${dIndex}][segments][${segmentCount}][sample_response]" class="form-control">
                            </div>

                            <div class="row mt-3">
                                <div class="col-sm-6">
                                    <label class="form-label">Answer (English)</label>
                                    <input type="text" name="dialogues[${dIndex}][segments][${segmentCount}][answer_eng]" 
                                        class="form-control" required>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label answer-languages-label">Answer ${selectedLang}</label>
                                    <input type="text" name="dialogues[${dIndex}][segments][${segmentCount}][answer_second_language]" 
                                        class="form-control" required>
                                </div>
                            </div>
                        </div>`;

                    wrapper.insertAdjacentHTML('beforeend', html);
                });
            });

        });
    </script>

@endsection
