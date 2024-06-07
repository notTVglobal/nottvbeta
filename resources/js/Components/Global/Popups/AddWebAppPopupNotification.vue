<template>
  <div v-if="showPopup" class="popup" @click.self="closePopup">
    <div class="popup-overlay"></div>
    <div class="popup-content border border-white scrollbar-custom dark:scrollbar-custom-dark">
      <span class="close" @click="closePopup">&times;</span>
      <h2 class="flex flex-wrap justify-center items-center">Add <img :src="logoSrc" class="w-20 h-auto"> to Your Home Screen</h2>
      <p class="mt-2">
        Experience notTV like never before by adding it to your home screen. This allows you to quickly access our
        platform, bypass censorship, and enjoy a seamless user experience.
      </p>
      <p class="mt-2">
        Follow these 3 simple steps to add notTV to your home screen:
      </p>
      <ol class="mt-2">
        <li>
          <strong>1. Open your browser's menu</strong> <br>
          <div class="flex gap-2 justify-start">
          <SingleImageWithModal
              :placeholder="`https://cdn.nottv.io/public/2024/06/images/Xo6uaMUzEyE0YIfTCfun3yiGZvQ4mq056s1bgq7n.png`"
              :alt="`Apple Browser menu icon`"
              :class="`w-20 h-20 object-contain my-2 transition-transform duration-300 ease-in-out hover:scale-110`"
              :caption="`iOS`"
          />
            <SingleImageWithModal
                :placeholder="`https://cdn.nottv.io/public/2024/06/images/8f8UbaNuOwLHBICGoM1De2zUhYcv976ATPXwUpFI.png`"
                :alt="`Android Browser menu icon`"
                :class="`w-20 h-20 object-contain my-2 transition-transform duration-300 ease-in-out hover:scale-110`"
                :caption="`Android`"
            />
          </div>
        </li>
        <li>
          <strong>2. Tap "Add to Home Screen"</strong> <br>
          <div class="flex gap-2 justify-start">
          <SingleImageWithModal
              :placeholder="`https://cdn.nottv.io/public/2024/06/images/bnxKWcskRQvS7sQEBKPeTXCaLx4QY48eko0ieOCr.png`"
              :alt="`Apple Add to Home Screen icon`"
              :class="`w-20 h-20 object-contain my-2 transition-transform duration-300 ease-in-out hover:scale-110`"
              :caption="`iOS`"
          />
            <SingleImageWithModal
                :placeholder="`https://cdn.nottv.io/public/2024/06/images/NlnU6ypPl2VFg9lD2FPU82SKrZZgnNVP3sXsD08E.png`"
                :alt="`Android Add to Home Screen icon`"
                :class="`w-20 h-20 object-contain my-2 transition-transform duration-300 ease-in-out hover:scale-110`"
                :caption="`Android`"
            />
          </div>
        </li>
        <li>
          <strong>3. Follow the prompts to add notTV</strong> <br>
          <div class="flex gap-2 justify-start">
            <SingleImageWithModal
                :placeholder="`https://cdn.nottv.io/public/2024/06/images/I9OAS7KYX3ohdsS7VbCJaBW0t1juJXzgBRSCRwfa.png`"
                :alt="`Follow prompts icon`"
                :class="`w-20 h-20 object-contain my-2 transition-transform duration-300 ease-in-out hover:scale-110`"
            />
            <SingleImageWithModal
                :placeholder="`https://cdn.nottv.io/public/2024/06/images/KCZJzt1QiMYCajjLoVIMgAWAeMAh18VSP0hYfget.png`"
                :alt="`Home Screen icon`"
                :class="`w-20 h-20 object-contain my-2 transition-transform duration-300 ease-in-out hover:scale-110`"
            />
          </div>
        </li>
      </ol>
      <p>
        By adding notTV to your home screen, you ensure you have direct access to our content, free from restrictions
        and enhanced for your convenience.
      </p>
      <button @click="closePopup" class="btn-close">Got it!</button>
    </div>
  </div>
</template>

<script setup>
import SingleImageWithModal from '@/Components/Global/Multimedia/SingleImageWithModal.vue'
import { computed, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  showPopup: {
    type: Boolean,
    required: true,
  },
})

const emit = defineEmits(['close'])

const closePopup = () => {
  emit('close')
}

const logoSrc = computed(() => {
  return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
      ? '/storage/images/logo_white.svg'
      : '/storage/images/logo_black_311.png';
});

const handleKeyDown = (event) => {
  if (event.key === 'Escape') {
    closePopup();
  }
};

onMounted(() => {
  document.addEventListener('keydown', handleKeyDown);
});

onUnmounted(() => {
  document.removeEventListener('keydown', handleKeyDown);
});
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

body {
  font-family: 'Roboto', sans-serif;
}

.popup {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 998;
  padding: 20px;
}

.popup-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.8);
  backdrop-filter: blur(10px);
  z-index: -1;
}

.popup-content {
  padding: 30px;
  background-color: whitesmoke;
  border-radius: 10px;
  box-shadow: 0 5px 30px rgba(0, 0, 0, 0.3);
  text-align: left;
  color: ;
  max-width: 500px;
  width: 100%;
  max-height: 80vh; /* Ensure the popup height does not exceed the viewport height */
  overflow-y: auto; /* Make content scrollable if it exceeds the popup height */
  position: relative;
}

.popup .close {
  position: absolute;
  top: 10px;
  right: 10px;
  font-size: 25px;
  cursor: pointer;
  color: black;
  transition: color 0.2s;
}

.popup .close:hover {
  color: #ea4335;
}

.popup h2 {
  margin-top: 0;
  font-size: 1.8em;
  color: #1a73e8;
}

.popup p {
  font-size: 1em;
  color: #202124;
}

.popup ol {
  padding-left: 20px;
}

.popup ol li {
  margin-bottom: 15px;
  color: #202124;
}

.instruction-image {
  width: 100px; /* Adjust size as needed */
  height: auto;
  display: block;
  margin: 10px 0;
}

.btn-close {
  display: inline-block;
  margin-top: 20px;
  padding: 10px 20px;
  background-color: #34a853;
  color: #ffffff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1em;
  transition: background-color 0.3s;
}

.btn-close:hover {
  background-color: #2a8535;
}

/* Hover effect for SingleImageWithModal */
.single-image {
  transition: transform 0.3s ease-in-out;
}

.single-image:hover {
  transform: scale(1.1);
}

/* Flexbox to align images side by side */
.flex {
  display: flex;
  gap: 10px;
  justify-content: flex-start;
}

/* Dark mode styling */
@media (prefers-color-scheme: dark) {
  .popup-content {
    background-color: #333333;
    color: #ffffff;
  }

  .popup h2,
  .popup p {
    color: #ffffff;
  }

  .popup ol li {
    color: #ffffff;
  }


  .popup .close {
    color: #bbbbbb;
  }

  .popup .close:hover {
    color: #ffffff;
  }

  .btn-close {
    background-color: #0056b3;
  }

  .btn-close:hover {
    background-color: #003d7a;
  }
}
</style>
