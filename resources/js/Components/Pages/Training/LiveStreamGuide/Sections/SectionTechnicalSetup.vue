<template>
  <div class="section-info gap-6">
    <div class="flex flex-col">
      <p>Explore the essential equipment and software needed for professional-quality live streaming. This section covers everything from cameras and microphones to encoding software.</p>
    </div>
  </div>
  <div class="section-content flex flex-col gap-6">
    <!-- Audio Setup -->
    <div class="role-card">
      <div class="role-icon bg-blue-500 text-white p-4 rounded-full">
        <!-- Placeholder for Audio Icon -->
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12 6.75L9 19z" />
        </svg>
      </div>
      <div class="role-info text-lg">
        <h3 class="font-bold text-xl">Audio Setup</h3>
        <p><strong>Diagram of audio flow:</strong> Connecting microphones to cameras or directly to the speaker system. <img src="placeholder-for-audio-flow-diagram.jpg" alt="Audio Flow Diagram" class="bg-yellow-200" style="width: 300px; height: 200px;"></p>
        <p><strong>Decision Points:</strong> Choosing between direct audio line-in or ambient mic setup based on venue acoustics.</p>

        <h4 class="font-semibold text-lg">Types of Microphones</h4>
        <ul>
          <li>
            <strong>Professional Wireless Microphones</strong> - Ideal for large events where mobility and range are important.
            <img src="placeholder-for-professional-wireless.jpg" alt="Professional Wireless Microphone" class="bg-yellow-200" style="width: 200px; height: 150px;">
          </li>
          <li>
            <strong>Bluetooth Wireless Microphones</strong> - Suitable for smaller venues or personal use, easy to connect with mobile devices.
            <img src="placeholder-for-bluetooth-wireless.jpg" alt="Bluetooth Wireless Microphone" class="bg-yellow-200" style="width: 200px; height: 150px;">
          </li>
          <li>
            <strong>Wired Adapters for Phones</strong> - Options include XLR, USB, and 3.5mm plugs. Essential for converting smartphones into recording devices in professional settings.
            <img src="placeholder-for-wired-adapters.jpg" alt="Wired Adapters for Phones" class="bg-yellow-200" style="width: 200px; height: 150px;">
          </li>
        </ul>
      </div>

    </div>
    <!-- Video Setup -->
    <div class="role-card">
      <div class="role-icon bg-green-500 text-white p-4 rounded-full">
        <!-- Placeholder for Video Icon -->
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.55-4.55m0 0L21 7m-5.45-1.55L15 10m0 0l-4.55-4.55M15 10l-4.55 4.55M10 15l-4.55 4.55M5.45 21L10 15M10 15H5m5 0v5m0-5l4.55-4.55M15 10l4.55-4.55M10 15l-4.55-4.55M5.45 5L10 10"/>
        </svg>
      </div>
      <div class="role-info text-lg">
        <h3 class="font-bold text-xl">Video Setup</h3>
        <p><strong>Camera choices and placement strategies:</strong> for optimal coverage.</p>
        <p><strong>Teachable Moments:</strong> Understanding lighting and avoiding common pitfalls like backlighting.</p>

        <h4 class="font-semibold text-lg">Types of Cameras and Tools</h4>
        <ul>
          <li>
            <strong>DSLRs:</strong> High-quality image, suitable for dynamic shooting environments.
            <img src="placeholder-for-dslrs.jpg" class="bg-yellow-200" alt="DSLR Camera" style="width: 200px; height: 150px;">
          </li>
          <li>
            <strong>Phone Cameras:</strong> Convenient for quick setups and casual documentation.
            <img src="placeholder-for-phone-cameras.jpg" class="bg-yellow-200" alt="Phone Camera" style="width: 200px; height: 150px;">
          </li>
          <li>
            <strong>Web Cameras:</strong> Ideal for stationary setups, commonly used for live streaming.
            <img src="placeholder-for-web-cameras.jpg" class="bg-yellow-200" alt="Web Camera" style="width: 200px; height: 150px;">
          </li>
          <li>
            <strong>Recording and Streaming Tools:</strong>
            <ul>
              <li>
                <strong>Atomos Ninja+Connect:</strong> For high-quality recording and instant replay on larger displays.
                <img src="placeholder-for-atomos-ninja.jpg" class="bg-yellow-200" alt="Atomos Ninja+Connect" style="width: 200px; height: 150px;">
              </li>
              <li>
                <strong>Camera for OBS Studio iPhone Software:</strong> Integrates mobile devices directly with OBS for streaming.
                <img src="placeholder-for-camera-for-obs.jpg" class="bg-yellow-200" alt="Camera for OBS Studio iPhone Software" style="width: 200px; height: 150px;">
              </li>
              <li>
                <strong>USB Web Cameras for OBS:</strong> Simple plug-and-play option for OBS streaming setups.
                <img src="placeholder-for-usb-web-cameras.jpg" class="bg-yellow-200" alt="USB Web Cameras for OBS" style="width: 200px; height: 150px;">
              </li>
            </ul>
          </li>
        </ul>

        <h4 class="font-semibold text-lg">Camera Functionality and Event Considerations</h4>
        <p>Assess if the camera can zoom and if it can be moved during the event without difficulty. For events with a single, stationary camera, ensure a wide enough shot to capture all presenters and significant activities.</p>
      </div>

    </div>
    <!-- Live Stream Software and Tools -->
    <div class="role-card">
      <div class="role-icon bg-red-500 text-white p-4 rounded-full">
        <!-- Placeholder for Software Icon -->
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8V4m0 16v-4m-8-4H4m16 0h-4M6.343 6.343l-1.414 1.414m14.142 0l-1.414-1.414M6.343 17.657l-1.414-1.414m14.142 0l-1.414 1.414"/>
        </svg>
      </div>
      <div class="role-info text-lg">
        <h3 class="font-bold text-xl">Live Stream Software and Tools</h3>
        <p><strong>Setup:</strong> Setting up OBS or Larix for streaming.</p>
        <p><strong>Stream Keys:</strong> are available on the notTV Go Live page.</p>
        <p><strong>Using tools like Zoom:</strong> for simpler setups or guest inclusion.</p>
        <p><strong>Ensuring Internet Connectivity:</strong> use tools like SpeedTest or Fast.com</p>
      </div>
    </div>
    <!-- Video Playback -->
    <div class="role-card">
      <div class="role-icon bg-orange-500 text-white p-4 rounded-full">
        <!-- SVG Icon for Video Playback -->
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.55-4.55M21 7m-5.45-1.55L15 10m0 0l-4.55-4.55M15 10l-4.55 4.55M10 15l-4.55 4.55M5.45 21L10 15M10 15H5m5 0v5m0-5l4.55-4.55M15 10l4.55-4.55M10 15l-4.55-4.55M5.45 5L10 10"/>
          <circle cx="12" cy="12" r="3.25"></circle>
          <path d="M20 20l-4.35-4.35M5 7h2m10 0h2m-6-4v2m0 12v2M5 15H3m18 0h-2"/>
        </svg>
      </div>
      <div class="role-info text-lg">
        <h3 class="font-bold text-xl">Video Playback</h3>
        <p><strong>Pre-recorded Content:</strong> How to integrate videos into the live stream using OBS or similar software. This includes playing back highlight reels, educational content, or introductory videos before, during, or after the live event.</p>
        <p><strong>Decision Points:</strong> When to play these videos to maximize viewer engagement and provide context or entertainment during downtime in the live event.</p>
      </div>
    </div>

  </div>
</template>

<script setup>
// import Exclamation from '@/Components/Icons/Exclamation.vue'

</script>

