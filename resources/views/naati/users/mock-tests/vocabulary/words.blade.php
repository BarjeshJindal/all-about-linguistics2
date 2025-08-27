@extends('layouts.vertical', ['title' => $category->name, 'topbarTitle' => $category->name])

@section('content')
<div class="col-xl-12">
        <div class="card p-4 pt-md-0">
            <div class="row">
        <div class="translate-mode-sec col-md-6 col-12 py-md-3">
            <form class="search-wrap my-md-4" role="search" action="/search" method="get">
            <label for="translationMode" class="search-label form-label ">Translation Mode:</label>
            <select id="translationMode" class="form-select" style=" border: 2px solid #e1c21e;">
                <option value="native_to_english" {{ request('direction') == 'native_to_english' ? 'selected' : '' }}>
                    Native ⇒ English
                </option>
                <option value="english_to_native" {{ request('direction') == 'english_to_native' ? 'selected' : '' }}>
                    English ⇒ Native
                </option>
            </select>
             </form>
        </div>
        <div class="translate-mode-sec col-md-6 col-12 py-3">
            <form class="search-wrap my-md-4" role="search" action="/search" method="get">
                <label for="q" class="search-label">Search Word</label>
                <div class="search-bar">
                <input type="search" id="q" name="q" class="search-input" placeholder="Search here…" aria-label="Search" />
                <button type="submit" class="search-btn">Search</button>
                </div>
            </form>
        </div>
        </div>
        <div class="translation-table">
    <table id="datatable-buttons" class="table table-bordered shadomw-sm table-striped dt-responsive nowrap w-100 text-center">
        <thead>
            <tr>
                <th>Sr. No</th>
                <th>Word</th>
                <th>Meaning</th>
                <th>Opened</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($words as $word)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $word->word }}</td>
                    <td>
                        <a href="javascript:void(0)"
                           class="meaning-link text-primary"
                           data-id="{{ $word->id }}"
                           data-meaning="{{ $word->meaning }}">
                           show meaning
                        </a>
                    </td>
                    <td class="times">Opened {{ $word->open_count }} times</td>
                    <td>
                        <button class="btn btn-success add-word" data-word-id="{{ $word->id }}">
                            + My Words
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No Words available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
</div>
<style>
  
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
// Show meaning + update count
$(document).on('click', '.meaning-link', function() {
    let $this = $(this);
    let meaning = $this.data('meaning');
    let wordId = $this.data('id');
    let $timesCell = $this.closest('tr').find('.times');

    $this.replaceWith('<span class="text-success fw-bold">' + meaning + '</span>');

    $.ajax({
        url: "{{ route('words.increment') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            word_id: wordId
        },
        success: function(response) {
            $timesCell.text('Opened ' + response.count + ' times');
        }
    });
});

// Add to My Words
$(document).on('click', '.add-word', function () {
    let wordId = $(this).data('word-id');

    $.ajax({
        url: "{{ route('add.my.word') }}",
        type: "POST",
        data: {
            word_id: wordId,
            _token: "{{ csrf_token() }}"
        },
        success: function (response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Added to My Words',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                });

                $(`button[data-word-id='${wordId}']`)
                    .text("Added")
                    .removeClass("btn-success")
                    .addClass("btn-secondary");
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Already Exists',
                    text: response.message
                });

                $(`button[data-word-id='${wordId}']`)
                    .text("Already Added")
                    .removeClass("btn-success")
                    .addClass("btn-secondary")
                    .prop("disabled", true);
            }
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong!'
            });
        }
    });
});
$('#translationMode').on('change', function () {
    let direction = $(this).val();
    window.location.href = "{{ route('vocabulary.words', $category->id ?? $category) }}?direction=" + direction;
});

</script>
@endsection
@section('styles')
    <!-- ✅ DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    @vite(['resources/js/pages/dashboard.js'])
@endsection
