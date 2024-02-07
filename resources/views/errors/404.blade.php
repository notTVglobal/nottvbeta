@extends('errors.layout')

@section('title', 'Page Not Found')

@section('message')
    <h1>Page Not Found</h1>
    <p>Sorry, but the page you were trying to view does not exist.</p>
    <a href="{{ url('/') }}">Go to Homepage</a>
@endsection
