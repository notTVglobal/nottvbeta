@extends('errors.layout')

@section('title', 'Access Forbidden')

@section('message')
    <h1>Access Forbidden</h1>
    <p>You do not have the necessary permissions to access this page.</p>
    <a href="{{ url('/') }}">Go to Homepage</a>
@endsection
