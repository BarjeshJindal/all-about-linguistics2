@extends('layouts.vertical', ['title' => 'Select Dialogues', 'topbarTitle' => 'Select Dialogues'])

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">

        {{-- ✅ Flash Messages --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.update-selected-dialogue', $plan->id) }}">
            @csrf

            {{-- Practice Dialogues --}}
            <h2>Practice Dialogues</h2>
            @forelse($practiceDialoguesByLang as $languageId => $dialogues)
                <h5 class="mt-4 mb-2">
                    <strong>Language:</strong> {{ $languages[$languageId]->second_language ?? 'Unknown' }}
                </h5>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="50">Select</th>
                            <th>#</th>
                            <th>Dialogue</th>
                            <th>Created at</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dialogues as $practiceDialogue)
                            <tr>
                                <td>
                                    <input type="checkbox"
                                        name="dialogues[1-{{ $practiceDialogue->id }}][selected]"
                                        value="1"
                                        {{ in_array('1-' . $practiceDialogue->id, $assigned) ? 'checked' : '' }}>
                                    <input type="hidden" name="dialogues[1-{{ $practiceDialogue->id }}][language_id]"
                                        value="{{ $practiceDialogue->language_id }}">
                                    <input type="hidden" name="dialogues[1-{{ $practiceDialogue->id }}][type_id]"
                                        value="1">
                                </td>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $practiceDialogue->title }}</td>
                                <td>{{ $practiceDialogue->created_at }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4">No practice dialogues available</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @empty
                <div class="text-center py-3 text-muted">No practice dialogues found for any language.</div>
            @endforelse

            {{-- VIP Dialogues --}}
            <h2 class="mt-5">VIP Exam Dialogues</h2>
            @forelse($vipDialoguesByLang as $languageId => $dialogues)
                <h5 class="mt-4 mb-2">
                    <strong>Language:</strong> {{ $languages[$languageId]->second_language ?? 'Unknown' }}
                </h5>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="50">Select</th>
                            <th>#</th>
                            <th>Dialogue</th>
                            <th>Created at</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dialogues as $vipDialogue)
                            <tr>
                                <td>
                                    <input type="checkbox"
                                        name="dialogues[2-{{ $vipDialogue->id }}][selected]"
                                        value="1"
                                        {{ in_array('2-' . $vipDialogue->id, $assigned) ? 'checked' : '' }}>
                                    <input type="hidden" name="dialogues[2-{{ $vipDialogue->id }}][language_id]"
                                        value="{{ $vipDialogue->language_id }}">
                                    <input type="hidden" name="dialogues[2-{{ $vipDialogue->id }}][type_id]"
                                        value="2">
                                </td>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $vipDialogue->title }}</td>
                                <td>{{ $vipDialogue->created_at }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4">No VIP dialogues available</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @empty
                <div class="text-center py-3 text-muted">No VIP dialogues found for any language.</div>
            @endforelse

            {{-- Mock Tests --}}
            <h2 class="mt-5">Mock Tests</h2>
            @forelse($mockTestsByLang as $languageId => $tests)
                <h5 class="mt-4 mb-2">
                    <strong>Language:</strong> {{ $languages[$languageId]->second_language ?? 'Unknown' }}
                </h5>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="50">Select</th>
                            <th>#</th>
                            <th>Test</th>
                            <th>Created at</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tests as $mockTest)
                            <tr>
                                <td>
                                    <input type="checkbox"
                                        name="dialogues[3-{{ $mockTest->id }}][selected]"
                                        value="1"
                                        {{ in_array('3-' . $mockTest->id, $assigned) ? 'checked' : '' }}>
                                    <input type="hidden" name="dialogues[3-{{ $mockTest->id }}][language_id]"
                                        value="{{ $mockTest->language_id }}">
                                    <input type="hidden" name="dialogues[3-{{ $mockTest->id }}][type_id]"
                                        value="3">
                                </td>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $mockTest->title }}</td>
                                <td>{{ $mockTest->created_at }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4">No mock tests available</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @empty
                <div class="text-center py-3 text-muted">No mock tests found for any language.</div>
            @endforelse

            <button type="submit" class="btn btn-primary mt-3">Save Dialogues</button>
        </form>
    </div>
</div>
@endsection
