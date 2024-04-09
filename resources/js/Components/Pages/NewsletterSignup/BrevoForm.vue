<template>
  <div class="vw-100">

    <iframe class="rounded-lg w-[calc(800px)] h-[calc(1200px)] md:h-[calc(900px)]" src="https://99fd701b.sibforms.com/serve/MUIFAFkjIVhYi3POZW-_1Je4F2Pd0vFh6Sz5K0tnZHADPGuRov8f-cP-agVnqqgrRkLhn8ZM_zQ4z8CpDPE1rg1tRN0UriYsXOrR5lLjnxosX1JGiqTFzfss92YFtOnva1a10aVkuLk2SJ6x-jmUdOQV2inT7GhN440SZjM4Mp-9WiS_Z8mEu6vL0WCGdn5iSuKcfMnKhSYS6VKV" frameborder="0" scrolling="auto" allowfullscreen style="display: block;margin-left: auto;margin-right: auto;max-width: 100%;"></iframe>

  </div>
</template>

<script setup>
import { onMounted } from 'vue';

// This example assumes you're handling form submission and reCAPTCHA verification within Vue.
// Consider using a Vue-specific reCAPTCHA library for better integration.
onMounted(() => {
  // Load reCAPTCHA script dynamically
  const script = document.createElement('script');
  script.src = 'https://www.google.com/recaptcha/api.js?hl=en';
  script.async = true;
  script.defer = true;
  document.head.appendChild(script);

  // Placeholder for your form submission logic
  const submitForm = () => {
    // Form submission logic here
  };

  // Example function to handle reCAPTCHA response
  window.handleCaptchaResponse = () => {
    // You might want to call your form submission or validation method here
    submitForm();
  };
});

// Cleanup if needed
// Consider removing the appended script or any global modifications on unmount.
</script>

<style scoped>
/* Include custom styles or font faces here */
@font-face {
  font-display: block;
  font-family: 'Roboto';
  src: url('https://assets.brevo.com/font/Roboto/Latin/normal/normal/7529907e9eaf8ebb5220c5f9850e3811.woff2') format('woff2'),
  url('https://assets.brevo.com/font/Roboto/Latin/normal/normal/25c678feafdc175a70922a116c9be3e7.woff') format('woff');
}
/* Repeat for other @font-face rules */

/* Tailwind does not directly handle placeholder color changes, so you might keep these as is or handle them with JavaScript */
#sib-container input::placeholder {
  color: #c0ccda;
}
</style>