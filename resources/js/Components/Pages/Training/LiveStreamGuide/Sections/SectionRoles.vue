<template>
  <div class="section-info gap-6">
    <div class="flex flex-col">
      <div>Understand the different roles involved in a live streaming setup, from technical support to content creators, and learn how each contributes to a successful broadcast.</div>
      <div class="flex flex-row gap-4 items-center mt-3">
        <Exclamation />
        <div class="text-xl"><span class="font-semibold">NOTE: </span> You'll need to assign key roles, <span class="font-semibold"> at least 1 person</span> who can be the live streaming technician and/or camera operator.</div>

      </div>
    </div>
 </div>
  <div class="section-content flex flex-col gap-6">
    <!-- Stream Coordinator -->
    <div class="role-card">
      <div class="role-icon bg-blue-500 text-white p-4 rounded-full">
        <!-- Placeholder for icon; use a real icon or image -->
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.55-4.55m0 0L21 7m-5.45-1.55L15 10m0 0l-4.55-4.55M15 10l-4.55 4.55M10 15l-4.55 4.55M5.45 21L10 15M10 15H5m5 0v5m0-5l4.55-4.55M15 10l4.55-4.55M10 15l-4.55-4.55M5.45 5L10 10" />
        </svg>
      </div>
      <div class="role-info text-lg">
        <h3 class="font-bold text-xl mb-2">Stream/Event Coordinator</h3>
        <p><strong>Responsibilities:</strong> Overseeing the entire streaming operation, coordinating between camera operators, technical operators, and ensuring the stream runs smoothly. This role may also encompass event coordination, managing on-site aspects of the event.</p>
        <p><strong>Additional Duties:</strong> Responsible for the notTV page where the live stream is pushed and recorded, making sure the page has an image and a description.</p>
        <p class="text-sm italic mt-2">Note: It's common in production for individuals to handle multiple roles. Flexibility and adaptability are key.</p>
      </div>
    </div>
    <!-- Camera Operator -->
    <div class="role-card">
      <div class="role-icon bg-red-500 text-white p-4 rounded-full">
        <!-- SVG Camera Icon -->
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.55-4.55M21 7m-5.45-1.55L15 10m0 0l-4.55-4.55M15 10l-4.55 4.55M10 15l-4.55 4.55M5.45 21L10 15M10 15H5m5 0v5m0-5l4.55-4.55M15 10l4.55-4.55M10 15l-4.55-4.55M5.45 5L10 10"/>
          <circle cx="12" cy="12" r="3"></circle>
          <path d="M20 20l-4.35-4.35M5 7h2m10 0h2m-6-4v2m0 12v2M5 15H3m18 0h-2"/>
        </svg>
      </div>
      <div class="role-info text-lg">
        <h3 class="font-bold text-xl mb-2">Camera Operator</h3>
        <p><strong>Responsibilities:</strong> Managing camera feeds, adjusting angles, and ensuring high-quality video output. If there is no Technical Operator the Camera Operator is responsible for all of those duties as well.</p>
        <p><strong>Teachable Moments:</strong> Best practices for framing and dynamic shooting during live events.</p>
      </div>
    </div>

    <!-- Audio Technician -->
    <div class="role-card">
      <div class="role-icon bg-purple-500 text-white p-4 rounded-full">
        <!-- SVG Microphone Icon for Audio Technician -->
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14a1 1 0 001-1V5a1 1 0 00-2 0v8a1 1 0 001 1zm0 6v-4m8-4a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <div class="role-info text-lg">
        <h3 class="font-bold text-xl mb-2">Audio Technician</h3>
        <p><strong>Responsibilities:</strong> Managing live audio feeds, balancing levels, and ensuring clear sound quality. Handles setup of microphones, mixers, and other audio equipment.</p>
        <p><strong>Decision Points:</strong> Choosing between direct audio feed or ambient microphone setups based on the acoustics of the venue.</p>
        <p><strong>Teachable Moments:</strong> Educating on the best practices for audio mixing and capturing during live events, handling feedback issues, and adapting to different acoustic environments.</p>
      </div>
    </div>


    <!-- Technical Operator -->
    <div class="role-card">
      <div class="role-icon bg-green-500 text-white p-4 rounded-full">
        <!-- SVG Technical Icon -->
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9.75V5.6a1 1 0 00-1-1h-1.8a1 1 0 00-1 1V9.75m0 4.5V18.4a1 1 0 001 1H11a1 1 0 001-1v-3.65" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 6.343a8 8 0 00-11.314 0m11.314 11.314a8 8 0 00-11.314 0M12 3v3.5M12 17.5V21m-9.192-9.192l2.474 2.474m12.436 0l-2.474-2.474" />
          <circle cx="12" cy="12" r="6.25" />
        </svg>
      </div>
      <div class="role-info text-lg">
        <h3 class="font-bold text-xl mb-2">Technical Operator</h3>
        <p><strong>Responsibilities:</strong> Handling the software, streaming setup, and technical troubleshooting. Responsible for ensuring good audio is going out to the live stream.</p>
        <p><strong>Decision Points:</strong> Selecting streaming software settings, managing connectivity issues.</p>
      </div>
    </div>

    <!-- MC aka Host -->
    <div class="role-card">
      <div class="role-icon bg-yellow-500 text-white p-4 rounded-full">
        <!-- SVG Icon for MC/Host -->
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.66 0 3-1.34 3-3S13.66 2 12 2 9 3.34 9 5s1.34 3 3 3zm0 2c-2.21 0-4-1.79-4-4S9.79 2 12 2s4 1.79 4 4-1.79 4-4 4zm0 2c2.67 0 8 1.34 8 4v2H4v-2c0-2.66 5.33-4 8-4zm-6 6v2h12v-2H6z"/>
        </svg>
      </div>
      <div class="role-info text-lg">
        <h3 class="font-bold text-xl mb-2">MC (Host)</h3>
        <p><strong>Responsibility:</strong> The MC ensures the online audience is continuously engaged and informed about the event's proceedings. This includes explaining any delays, upcoming segments, and interacting with viewers to maintain their interest and participation.</p>
        <p><strong>Importance:</strong> Crucial for keeping the virtual audience connected, especially since they do not experience the physical event atmosphere and may need additional context to remain engaged.</p>
      </div>
    </div>

  </div>
</template>

<script setup>
// You can include any section-specific JavaScript here.
// This could include data properties, computed properties, methods, and so forth.
import Exclamation from '@/Components/Icons/Exclamation.vue'
</script>
