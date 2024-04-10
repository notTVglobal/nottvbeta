@extends('errors.layout')

@section('title', 'Channel Restricted')

@section('message')
    <h1>Oops! This Channel is Restricted</h1>
    @if(isset($exception) && !empty($exception->getMessage()))
        <p>{{ $exception->getMessage() }}</p>
    @else
        <p>It seems you've stumbled upon a VIP area without the right pass. Don’t worry, there’s plenty more to explore and enjoy!</p>
    @endif
    <a href="{{ url('/') }}" class="btn btn-primary">Return to the Main Stage</a>
    <p>If you believe this is a mistake or need access, feel free to reach out. We're all about community here!</p>
@endsection
