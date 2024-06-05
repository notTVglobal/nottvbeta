@extends('errors.layout')

@section('title', 'Oops! Technical Glitch Detected')

@section('message')
    <h1>Uh-oh! Our Gears Got Jammed!</h1>
    @if(isset($exception) && !empty($exception->getMessage()))
        <p>{{ $exception->getMessage() }}</p>
    @else

    @endif
    <p>Looks like we've hit a snag on our end. No need to adjust your set; our tech wizards are already on it, working their magic to smooth things out. We appreciate your patience and understanding.</p>
    <a href="{{ url('/') }}" class="btn btn-primary">Back to the Main Scene</a>
    <p>In the meantime, why not take a moment to relax? We'll have everything up and running again shortly. Thanks for being part of our community!</p>
@endsection
