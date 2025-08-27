@extends('layouts.vertical', ['title' => 'My Words', 'topbarTitle' => 'My Words'])

@section('content')

   <div class="col-xl-12">
        <div class="card p-4 pt-md-0">
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
        <div class="translation-table">
        <table id="datatable-buttons" class="table table-bordered table-striped dt-responsive nowrap w-100 text-center">
                <thead>
                    <tr>
                        <th>Sr. No</th>
                        <th>Word</th>
                        <th>Meaning</th>
                        <th>Memorized</th>
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
                                data-id="{{ $word->word_id }}"
                                data-meaning="{{ $word->meaning }}">
                                Show Meaning
                                </a>
                            </td>
                            <td class="times">Memorized {{ $word->memorized_count }} times</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No Words available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
      </div>
    </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('click', '.meaning-link', function() {
        let $this = $(this);
        let meaning = $this.data('meaning');
        let wordId = $this.data('id');
        let $row = $this.closest('tr');
        let $timesCell = $row.find('.times');

        // Replace link with the meaning
        $this.replaceWith('<span class="text-success fw-bold">' + meaning + '</span>');

        // Send AJAX request to increment memorized count
        $.ajax({
            url: "{{ route('vocabulary.my-words.memorized') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                word_id: wordId
            },
            success: function(response) {
                $timesCell.text('Memorized ' + response.count + ' times');
            }
        });
    });

    $('#translationMode').on('change', function () {
            let direction = $(this).val();
            window.location.href = "{{ route('vocabulary.my-words') }}?direction=" + direction;
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
