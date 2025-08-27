@extends('layouts.vertical', ['title' => 'Create Practice Dialogue', 'topbarTitle' => 'Create Practice Dialogue'])

@section('content')
    <div>
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

        <form action="{{ route('admin.practices.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div>
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="5" id="practice_description"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="language">Second Language</label>
                    <select id="language" name="second_language" class="form-control">
                        <option>Select second language</option>
                        @foreach ($languages as $language)
                            <option value="{{ $language->id }}">{{ ucfirst($language->second_language) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div id="segment-wrapper">
                <div class="segment-block mb-4 mt-4 border p-6 rounded-2xl bg-light">
                    <h5 class="segment-title mb-3">Segment 1</h5>
                    <div class="mb-1 p-2">
                        <label class="form-label">Audio File(MP3)</label>
                        <input type="file" name="segments[0][segment_path]" class="form-control" accept=".mp3,.wav"
                            required>
                    </div>
                    <div class="row p-2">
                        <div class="mb-1 p-2 col-sm-6">
                            <label class="form-label">Answer(English)</label>
                            <input type="text" name="segments[0][answer_eng]" class="form-control" required>
                        </div>
                        <div class="mb-1 p-2 col-sm-6">
                            <label class="form-label answer-language-label">Answer Second Language</label>
                            <input type="text" name="segments[0][answer_second_language]" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" id="add-segment" class="btn btn-secondary Add-Segment">Add Segment</button>
            <button type="submit" class="btn btn-success submit">SUBMIT</button>
        </form>
    </div>
    <style>
        button.submit {
            background: #e1c21eed;
            border-color: #e1c21e;
        }

        button.submit:hover {
            background: #e1c21ecf;
            border-color: #e1c21e;
        }

        button.Add-Segment {
            background: #10c469;
            border-color: #10c469;
        }

        button.Add-Segment:hover {
            background: #10c46adc;
            border-color: #10c46adc;
        }
    </style>

    <script>
        let segmentIndex = 1;

        // Update all Answer(...) labels when language changes
        document.getElementById('language').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex].text || 'Second Language';
            document.querySelectorAll('.answer-language-label').forEach(label => {
                label.textContent = `Answer ${capitalizeFirstLetter(selected)} `;
            });
        });

        // Capitalize helper
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        // Add new segment and include updated label
        document.getElementById('add-segment').addEventListener('click', function() {
            const wrapper = document.getElementById('segment-wrapper');
            const languageSelect = document.getElementById('language');

            // ✅ Max 6 segments
            const currentSegments = wrapper.querySelectorAll('.segment-block').length;
            if (currentSegments >= 6) {
                Swal.fire({
                icon: 'warning',
                title: 'Limit Reached',
                text: 'You can only add up to 6 segments.',
                confirmButtonText: 'OK'
                });                
                return;
            }

            let selectedLanguage = "Second Language";
            if (languageSelect.selectedIndex > 0) {
                selectedLanguage = languageSelect.options[languageSelect.selectedIndex].text || "Second Language";
            }

            const newBlock = document.createElement('div');
            newBlock.classList.add('segment-block', 'mb-4', 'mt-4', 'border', 'p-6', 'rounded-2xl', 'bg-light');
            newBlock.innerHTML = `
                    <h5 class="segment-title mb-3">Segment ${segmentIndex + 1}</h5>   <!-- ✅ heading -->
                    <div class="mb-3 p-2">
                        <label class="form-label">Audio File(MP3)</label>
                        <input type="file" name="segments[${segmentIndex}][segment_path]" class="form-control" accept=".mp3,.wav" required>
                    </div>
                    <div class="row p-2">
                        <div class="mb-3 p-2 col-sm-6">
                            <label class="form-label">Answer(English)</label>
                            <input type="text" name="segments[${segmentIndex}][answer_eng]" class="form-control" required>
                        </div>
                        <div class="mb-3 p-2 col-sm-6">
                            <label class="form-label answer-language-label">Answer ${capitalizeFirstLetter(selectedLanguage)}</label>
                            <input type="text" name="segments[${segmentIndex}][answer_second_language]" class="form-control" required>
                        </div>
                    </div>
                `;
            wrapper.appendChild(newBlock);
            segmentIndex++;
        });
    </script>
@endsection

@section('scripts')
    @vite(['resources/js/pages/dashboard.js'])
@endsection
