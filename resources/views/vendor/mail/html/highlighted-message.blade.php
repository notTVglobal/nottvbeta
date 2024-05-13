@props([
    'message',
    'fromName'
])

<!-- Start of the Highlighted Message Component -->
<div style="background-color: #f0f8ff; padding: 15px; border-radius: 5px; margin: 20px 0;">
    <blockquote style="margin: 0; font-size: 18px; color: #1a1a1a;">
        <p style="margin: 0; font-weight: bold;">"{{ $message }}"</p>
    </blockquote>
    <p style="margin: 10px 0 0 0; font-size: 16px; color: #1a1a1a; text-align: right;">
        ~ <span style="font-style: italic;">{{ $fromName }}</span>
    </p>
</div>
<!-- End of the Highlighted Message Component -->
