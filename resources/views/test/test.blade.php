@extends('layouts.vertical', ['title' => 'Dashboard', 'topbarTitle' => 'Dashboard'])

@section('content')


<div>
  <a href="{{ route('test') }}">Audio Recording</a>
</div>





@endsection

@section('scripts')
@vite(['resources/js/pages/dashboard.js'])
@endsection