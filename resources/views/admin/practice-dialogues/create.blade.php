@extends('layouts.vertical', ['title' => 'Create Practice Dialogue', 'topbarTitle' => 'Create Practice Dialogue'])

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
                    <select id="language" name="language_id" class="form-control" required>
                        <option>Select second language</option>
                        @foreach ($languages as $language)
                            <option value="{{ $language->id }}">{{ ucfirst($language->second_language) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="category">Category</label>

                    @if ($categories->isEmpty())
                        <div class="alert alert-warning">
                            No categories available. 
                            <a href="{{ route('admin.category.index') }}" class="text-primary">
                                Click here to add a category.
                            </a>
                        </div>
                    @else
                        <select id="category" name="category_id" class="form-control" required>
                            <option value="">Select category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ ucfirst($category->name) }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
            </div>

            <div id="segment-wrapper">
            @php
                $oldSegments = old('segments', [ [] ]); // At least 1 empty segment
            @endphp

            @foreach ($oldSegments as $i => $segment)
                <div class="segment-block mb-4 mt-4 border p-6 rounded-2xl bg-light rounded-2">
                    <h4 class="segment-title p-2">Segment {{ $i + 1 }}</h4>
                    
                   <div class="mb-1 p-2">
    <label class="form-label">Audio File (MP3)</label>
    <input type="file" 
        name="segments[{{ $i }}][segment_path]" 
        class="form-control @error("segments.$i.segment_path") is-invalid @enderror" 
        accept=".mp3,.wav"
        required> {{-- ✅ Make required --}}
    @error("segments.$i.segment_path")
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-1 p-2">
    <label class="form-label">Sample Response (MP3)</label>
    <input type="file" 
        name="segments[{{ $i }}][sample_response]" 
        class="form-control @error("segments.$i.sample_response") is-invalid @enderror" 
        accept=".mp3,.wav"
        required> {{-- ✅ Make required --}}
    @error("segments.$i.sample_response")
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row p-2">
    <div class="mb-1 p-2 col-sm-6">
        <label class="form-label">Answer (English)</label>
        <input type="text" 
            name="segments[{{ $i }}][answer_eng]" 
            value="{{ old("segments.$i.answer_eng") }}" 
            class="form-control @error("segments.$i.answer_eng") is-invalid @enderror"
            required> {{-- ✅ Make required --}}
        @error("segments.$i.answer_eng")
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-1 p-2 col-sm-6">
        <label class="form-label answer-languages-label">Answer Second Language</label>
        <input type="text" 
            name="segments[{{ $i }}][answer_second_language]" 
            value="{{ old("segments.$i.answer_second_language") }}" 
            class="form-control @error("segments.$i.answer_second_language") is-invalid @enderror"
            required> {{-- ✅ Make required --}}
        @error("segments.$i.answer_second_language")
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
                </div>
            @endforeach
        </div>

            <button type="button" id="add-segment" class="btn btn-secondary Add-Segment">Add Segment</button>
            <button type="submit" class="btn btn-success submit">SUBMIT</button>
        </form>
    </div>
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
    // Update all Answer(...) labels when language changes
    document.getElementById('language').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex].text || 'Second Language';
        document.querySelectorAll('.answer-languages-label').forEach(label => {
            label.textContent = `Answer ${capitalizeFirstLetter(selected)} `;
        });
    });

    // Capitalize helper
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    // Add new segment (no numeric index!)
  // Add new segment
document.getElementById('add-segment').addEventListener('click', function() {
    const wrapper = document.getElementById('segment-wrapper');

    // ✅ Count existing segments for correct index
    let index = wrapper.querySelectorAll('.segment-block').length;

    // Get selected language for label
    let selectedLanguage = "Second Language";
    const languageSelect = document.getElementById('language');
    if (languageSelect.selectedIndex > 0) {
        selectedLanguage = languageSelect.options[languageSelect.selectedIndex].text || "Second Language";
    }

    // Create new segment block
    const newBlock = document.createElement('div');
    newBlock.classList.add('segment-block', 'mb-4', 'mt-4', 'border', 'p-6', 'rounded-2xl', 'bg-light');
    newBlock.innerHTML = `
        <h4 class="segment-title p-2">Segment ${index + 1}</h4>
        <div class="mb-3 p-2">
            <label class="form-label">Audio File (MP3)</label>
            <input type="file" name="segments[${index}][segment_path]" class="form-control" accept=".mp3,.wav" required>
        </div>
        <div class="mb-1 p-2">
            <label class="form-label">Sample Response (MP3)</label>
            <input type="file" name="segments[${index}][sample_response]" class="form-control" accept=".mp3,.wav" required>
        </div>
        <div class="row p-2">
            <div class="mb-3 p-2 col-sm-6">
                <label class="form-label">Answer (English)</label>
                <input type="text" name="segments[${index}][answer_eng]" class="form-control" required>
            </div>
            <div class="mb-3 p-2 col-sm-6">
                <label class="form-label answer-languages-label">Answer ${capitalizeFirstLetter(selectedLanguage)}</label>
                <input type="text" name="segments[${index}][answer_second_language]" class="form-control" required>
            </div>
        </div>
    `;

    // ✅ Ensure file inputs are required
    newBlock.querySelectorAll('input[type="file"]').forEach(input => input.required = true);

    // Append the new block
    wrapper.appendChild(newBlock);

    // ✅ Auto-focus first text input for UX
    const firstInput = newBlock.querySelector('input[type="text"]');
    if (firstInput) firstInput.focus();
});

</script>
@endsection

@section('scripts')
    @vite(['resources/js/pages/dashboard.js'])
@endsection
