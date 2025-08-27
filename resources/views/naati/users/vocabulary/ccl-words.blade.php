@extends('layouts.vertical', ['title' => 'CCL Words', 'topbarTitle' => 'CCL Words'])

@section('content')
    <div class="col-xl-12">
        <div class="card p-4 pt-md-0">
            <div class="translate-mode-sec col-md-6 col-12 py-3">
                <form class="search-wrap my-md-4" role="search" action="/search" method="get">
                <label for="translationMode" class="search-label form-label">Translation Mode:</label>
                <select id="translationMode" class="form-select" style="border: 2px solid #e1c21e;">
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
                                <a href="javascript:void(0)" class="meaning-link text-primary" data-id="{{ $word->word_id }}"
                                    data-meaning="{{ $word->meaning }}">
                                    Show Meaning
                                </a>
                            </td>
                            <td class="times">Opened {{ $word->open_count }} times</td>
                            <td>
                                <button class="btn btn-success add-word" data-word-id="{{ $word->word_id }}">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Show meaning + update open count
        $(document).on('click', '.meaning-link', function() {
            let $this = $(this);
            let meaning = $this.data('meaning');
            let wordId = $this.data('id');
            let $timesCell = $this.closest('tr').find('.times');

            // Replace link with meaning text
            $this.replaceWith('<span class="text-success fw-bold">' + meaning + '</span>');

            // Send AJAX request to increment count
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

        // Add word to "My Words"
        $(document).on('click', '.add-word', function() {
            let wordId = $(this).data('word-id');
            let $button = $(this);

            $.ajax({
                url: "{{ route('add.my.word') }}",
                type: "POST",
                data: {
                    word_id: wordId,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Added!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });

                        // Update button state
                        $button.text("Added")
                            .removeClass("btn-custom")
                            .addClass("btn-secondary")
                            .prop("disabled", true);
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Already Exists',
                            text: response.message
                        });
                    }
                },
                error: function() {
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
            window.location.href = "{{ route('vocabulary.ccl-words') }}?direction=" + direction;
        });
    </script>
@endsection

@section('styles')
    <!-- ✅ DataTables Bootstrap 5 CSS -->
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"> --}}
@endsection

@section('scripts')
    <!-- ✅ DataTables with Bootstrap 5 -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    {{-- <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script> --}}

    <script>
        $(document).ready(function() {
            $('#datatable-buttons').DataTable({
                pageLength: 10,
                lengthChange: true,
                searching: true,
                ordering: true,
                autoWidth: false,
                language: {
                    search: "", // removes the default label
                    searchPlaceholder: "Search words..."
                },
                initComplete: function () {
                    // Add Bootstrap styling to search box
                    $('.dataTables_filter input')
                        .addClass('form-control form-control-sm')
                        .css({'display':'inline-block','width':'250px'});
                }
            });
        });
    </script>
@endsection
