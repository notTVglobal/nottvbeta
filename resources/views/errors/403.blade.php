@extends('errors.layout')

@section('title', 'Access Forbidden')

@section('message')
    <h1>Access Forbidden</h1>
    @if(isset($exception) && !empty($exception->getMessage()))
        <p>{{ $exception->getMessage() }}</p>
    @else
        <p>You do not have the necessary permissions to access this page.</p>
    @endif
    <a href="{{ url('/') }}">Go to Homepage</a>
@endsection