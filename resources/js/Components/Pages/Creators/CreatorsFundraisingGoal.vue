<template>
  <div>
    <!-- Fundraising Goal Section -->
    <div class="w-full mx-auto bg-white p-6 rounded-lg shadow-lg mb-12">
      <h2 class="text-3xl font-bold pb-3 text-center text-gray-800">Fundraising Goal</h2>
      <p class="text-xl mb-4 text-center text-gray-600">Help {{ creator.name }} reach their goal!</p>
      <div class="w-full bg-gray-300 rounded-full h-8 mb-4 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-full rounded-full flex items-center justify-center text-white text-sm font-bold" :style="{ width: fundraisingProgress + '%' }">
          {{ fundraisingProgress.toFixed(2) }}%
        </div>
      </div>
      <p class="text-lg text-center text-gray-700 mb-6">{{ fundraisingAmountRaised }} raised of {{ fundraisingGoal }}</p>
      <div class="flex justify-center">
        <button class="bg-gradient-to-r from-green-400 to-green-600 text-white py-3 px-6 rounded-full shadow-md hover:shadow-lg transition duration-300 ease-in-out">
          <font-awesome-icon icon="fa-donate" class="mr-2"/>
          Donate Now
        </button>
      </div>
    </div>

    <!-- Fundraising Details Section -->
    <div class="w-full mx-auto bg-white p-6 rounded-lg shadow-lg mb-12">
      <h2 class="text-3xl font-bold pb-3 text-center text-gray-800 mb-4">About Our Fundraising</h2>
      <div :class="detailsFlexClass">
        <img src="#" alt="Fundraising Image" class="skeleton w-full h-auto max-w-4xl rounded-lg shadow-md mb-6 lg:mb-0 lg:mr-6"/>
        <div class="text-center lg:text-left max-w-4xl">
          <h3 class="text-2xl font-semibold text-gray-800 mb-4">Fundraising for a Great Cause</h3>
          <p class="text-lg text-gray-700">We are raising funds to support our latest project. Your contributions will help us reach new heights and achieve our goals. Every donation counts and brings us closer to success. Thank you for your support!</p>
          <p class="mt-4 text-gray-700">{{ creator.description }}</p>
          <SocialMediaBadges :links="creator.social_links" class="mt-6"/>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, computed } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faDonate } from '@fortawesome/free-solid-svg-icons'
import { usePage } from '@inertiajs/vue3'
import BackButton from '@/Components/Global/Buttons/BackButton.vue';
import ShareButton from '@/Components/Global/UserActions/ShareButton.vue'
import SocialMediaBadges from '@/Components/Global/Badges/SocialMediaBadgeLinks.vue'

const props = defineProps({
  creator: Object,
});

const page = usePage()

// Sample data for fundraising
const fundraisingGoal = 10000; // Example goal amount
const fundraisingAmountRaised = 5000; // Example raised amount
const fundraisingProgress = (fundraisingAmountRaised / fundraisingGoal) * 100;

const detailsFlexClass = computed(() => {
  return page.props.auth.user ? 'flex flex-col xl:flex-row items-center' : 'flex flex-col lg:flex-row items-center';
});
</script>

<style scoped>
/* Tailwind CSS styles */
.bg-gradient-to-r {
  background: linear-gradient(to right, #3b82f6, #2563eb); /* Blue gradient for the progress bar */
}

.bg-gradient-to-r.from-green-400.to-green-600 {
  background: linear-gradient(to right, #34d399, #10b981); /* Green gradient for the button */
}

.shadow-lg {
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); /* Larger shadow */
}

.hover\:shadow-lg:hover {
  box-shadow: 0 15px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); /* Larger shadow on hover */
}

.text-gray-800 {
  color: #1f2937; /* Dark gray text color */
}

.text-gray-600 {
  color: #4b5563; /* Lighter gray text color */
}

.text-gray-700 {
  color: #374151; /* Medium gray text color */
}

/* Skeleton loading class */
.skeleton {
  background-color: #e0e0e0;
  border: 1px solid #ccc;
  height: 300px; /* Adjust this value based on desired height */
}
</style>
