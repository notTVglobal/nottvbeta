<template>
  <Head title="Change Log"/>

  <div id="topDiv" class="font-sans antialiased">
    <div class="pt-4 rounded bg-white">
      <div class="flex flex-col items-center pt-6 sm:pt-0 pb-32">
        <div>
          <img :src="`/storage/images/logo_black_311.png`" alt="image" class="w-1/2 justify-center">
        </div>

        <div class="changelog w-full h-fit mt-10 mb-32 px-4 overflow-scroll">
                        <span class="mt-6 p-6 text-gray-900 shadow-md sm:rounded-lg prose"
                              v-html="changelog"/>
        </div>

      </div>
    </div>
  </div>

</template>

<script setup>
import { onMounted } from "vue"
import { Head } from '@inertiajs/vue3'
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
import { usePageSetup } from '@/Utilities/PageSetup'

usePageSetup('changeLog')

defineProps({
  changelog: String,
});

</script>

<style scoped>
.changelog {
  height: 80vh;
}

pre {
  overflow-x: auto;
  white-space: pre-wrap;
  white-space: -moz-pre-wrap;
  white-space: -pre-wrap;
  white-space: -o-pre-wrap;
  word-wrap: break-word;
}

</style>
