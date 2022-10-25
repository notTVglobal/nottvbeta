<!-- resources/views/chat.blade.php -->
{{-- This page is created for testing the setup of chat only. --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }} Chat</title>

</head>
<body class="font-sans antialiased bg-black">

<div class="container">
    <div class="card">
        <div class="card-header">Conversation 77</div>
        <div class="card-body">
            <chat-container></chat-container>
        </div>
        <div class="card-footer">
{{--            <chat-form v-on:messagesent="addMessage" :user="{{ Auth::user() }}"></chat-form>--}}
        </div>
    </div>
</div>

</body>
</html>
