@extends('errors.layout')

@section('title', 'Whoops! Lost in Transmission?')

@section('message')
    <h1>Seems Like a Dead End!</h1>
    <p>Oops! Looks like the content you were searching for is playing hide-and-seek. It might have moved or doesn't exist anymore. But don't worry, we've got plenty of other exciting channels to explore!</p>
    <a href="{{ url('/') }}" class="btn btn-primary">Back to the Main Screen</a>
{{--    <p>Or, if you feel adventurous, use our search to find something inspiring, creative, or just fun!</p>--}}
@endsection