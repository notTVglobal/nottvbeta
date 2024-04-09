<template>
  <div>
    <h1 class="text-2xl mb-2 text-center text-indigo-600"><slot name="header"></slot></h1>
    <p class="mb-6 text-center text-indigo-600"><slot name="subHeader" ></slot></p>

    <p class="mt-6 mb-4 px-8 text-left">Here's what you need to know:</p>
    <ul class="list-disc space-y-2 mb-6 px-8 mx-auto text-left text-black font-medium">
      <li>The countdown to your next live is on your Manage Show page and Dashboard.</li>
      <li>In case of scheduling conflicts, priority is determined on a first-come, first-served basis. Early
        scheduling takes precedence.
      </li>
      <li>Changing your schedule is possible at any time, but be aware of priority spot changes.</li>
      <li>Creators are limited to 3 shows during our beta phase to ensure quality and accessibility.</li>
      <li>Ensure your live stream is connected at least 5 minutes before your scheduled live to maintain
        priority.
      </li>
      <li>Unable to go live? Schedule a pre-recorded episode to keep your priority spot.</li>
    </ul>

    <div class="text-center mt-12">
      <h3 class="text-xl mb-2 text-orange-600">Ready to grow your audience?</h3>
      <p class="mb-4">Invite creators and fans to join notTV with these invite codes.</p>
    </div>

    <div class="flex justify-center items-center space-x-4 mt-4 mb-6">
      <button
          class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
        Invite Creator
      </button>
      <button
          @click.prevent=""
          class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
        View My Invite Codes
      </button>
    </div>
  </div>
</template>

<script setup>

</script>