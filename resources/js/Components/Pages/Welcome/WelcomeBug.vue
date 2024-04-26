<template>
  <Transition
      enter-from-class="opacity-0 scale-125"
      enter-to-class="opacity-100 scale-100"
      enter-active-class="transition duration-300"
      leave-active-class="transition duration-200"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-125"
  >
    <div v-if="welcomeStore.showOverlay===false">
      <div class="absolute top-4 lg:top-10 left-5 md:left-20">
        <div class="flex flex-row">
          <div>
            <img :src="`/storage/images/logo_white.svg`" class="w-20 md:w-40 pt-0 lg:pt-4 mt-0 lg:mt-0" alt="notTV logo">
          </div>
        </div>
      </div>
      <Transition
          leave-active-class="transition duration-200"
          leave-from-class="opacity-100 scale-100"
          leave-to-class="opacity-0 scale-125"
      >
      <div v-if="isVisible" class="absolute top-20 md:top-18 mt-20 lg:mt-12 flex flex-col left-5 md:left-20 ">
        <div class="font-semibold text-3xl" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.9);">Welcome to notTV</div>
        <div class="text-2xl" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.9);">Independent news, arts and culture.</div>
        <div class="text-2xl font-bold italic text-secondary" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.9);">
          Coming Soon!
        </div>
      </div>
      </Transition>
    </div>
  </Transition>
</template>

<script setup>
import { useWelcomeStore } from "@/Stores/WelcomeStore"
import { onMounted, ref } from 'vue'

const welcomeStore = useWelcomeStore()

const isVisible = ref(true)

onMounted(() => {
  isVisible.value = true
  setTimeout(() => {
    isVisible.value = false
  }, 10000);
});

</script>
