@extends('errors.layout')

@section('title', 'Internal Server Error')

@section('message')
    <h1>Internal Server Error</h1>
    <p>We've encountered a problem. We're working on getting it fixed as soon as we can.</p>
    <a href="{{ url('/') }}">Go to Homepage</a>
@endsection
