@props([
    'teamName',
    'fromName'
])

<!-- Start of the Highlighted Team Component -->
<div style="background-color: #f0f8ff; padding: 15px; border-radius: 5px; margin: 20px 0;">
    <p style="font-size: 20px; color: #1a1a1a; text-align: center;">
        <strong>{{ $teamName }}</strong>
    </p>
    <p style="margin: 10px 0 0 0; font-size: 18px; color: #1a1a1a; text-align: center;">
        ~ <span style="font-style: italic;">{{ $fromName }}</span>
    </p>
</div>
<!-- End of the Highlighted Team Component -->
