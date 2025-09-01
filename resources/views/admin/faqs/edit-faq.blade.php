@extends('layouts.vertical', ['title' => 'Edit Faq', 'topbarTitle' => 'Edit Faq'])

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

        <form action="{{ route('admin.faqs.update',$faq->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div>
                <div class="mb-3">
                    <label class="form-label">Question</label>
                    <input type="text" value="{{ $faq->question}}" name="question" class="form-control"  required>
                    {{-- <textarea class="form-control" name="question" rows="5" id="practice_description" required></textarea> --}}
                </div>
                <div class="mb-3">
                    <label class="form-label">Answer</label>
                    <textarea class="form-control"  name="answer" rows="5"  required> {{ $faq->answer}}</textarea>
                </div>
               
            </div>

       

           
            <button type="submit" class="btn btn-success submit">UPDATE</button>
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

    
@endsection

@section('scripts')
    @vite(['resources/js/pages/dashboard.js'])
@endsection
