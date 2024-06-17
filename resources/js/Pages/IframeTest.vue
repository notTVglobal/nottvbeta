<template>
  <Head title="iFrame Test"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div v-if="url">
      <iframe
          :src="url"
          v-if="!error"
          class="w-full h-screen"
          frameborder="0"
          sandbox="allow-same-origin allow-scripts allow-popups allow-forms"
          @load="onLoad"
          @error="onError"
      ></iframe>
      <div v-if="error" class="text-center text-red-500">
        <p>Cannot display this content in an iFrame. <a :href="url" target="_blank" class="text-blue-500 underline">Open in a new window</a>.</p>
      </div>
    </div>
    <div v-else>
      <p>No URL provided for preview.</p>
    </div>
    <div v-if="loading" class="text-center">Loading...</div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { usePageSetup } from '@/Utilities/PageSetup';

usePageSetup('library');

let props = defineProps({
  can: Object,
  url: {
    type: String,
    required: false,
    default: 'https://traviscross.ca/'
  }
});

const loading = ref(true);
const error = ref(false);

const onLoad = () => {
  loading.value = false;
};

const onError = () => {
  loading.value = false;
  error.value = true;
};
</script>
