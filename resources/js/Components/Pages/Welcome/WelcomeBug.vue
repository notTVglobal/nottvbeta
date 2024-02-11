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
      <div class="absolute top-6 md:top-4 left-5 md:left-20">
        <div class="flex flex-row">
          <div>
            <img :src="`/storage/images/logo_white_512.png`" class="w-20 md:w-40 pt-2">
          </div>

        </div>
      </div>
      <div class="absolute flex flex-col left-5 md:left-20 mt-10 md:mt-16">
        <div class="font-semibold text-3xl">Welcome to notTV</div>
        <div class="text-2xl">Independent news, arts and culture.</div>
        <div class="text-4xl font-bold italic text-secondary">
          Coming Soon!
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { useWelcomeStore } from "@/Stores/WelcomeStore"

const welcomeStore = useWelcomeStore()

</script>
