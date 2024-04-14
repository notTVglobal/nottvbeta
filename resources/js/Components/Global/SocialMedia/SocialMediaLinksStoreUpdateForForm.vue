<template>
  <div>
    <div class="mb-6">
      <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200" for="www_url">
        Website URL
      </label>
      <input v-model="form.www_url"
             class="border border-gray-400 p-2 w-full rounded-lg text-black"
             type="text"
             id="www_url"
      >
      <div v-if="form.errors.www_url" v-text="form.errors.www_url" class="text-xs text-red-600 mt-1"></div>
    </div>

    <div class="mb-6">
      <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200" for="instagram_name">
        Instagram Handle
      </label>
      <input v-model="form.instagram_name"
             class="border border-gray-400 p-2 w-full rounded-lg text-black"
             type="text"
             id="instagram_name"
      >
      <div v-if="form.errors.instagram_name" v-text="form.errors.instagram_name" class="text-xs text-red-600 mt-1"></div>
    </div>

    <div class="mb-6">
      <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200" for="telegram_url">
        Telegram URL
      </label>
      <input v-model="form.telegram_url"
             class="border border-gray-400 p-2 w-full rounded-lg text-black"
             type="text"
             id="telegram_url"
      >
      <div v-if="form.errors.telegram_url" v-text="form.errors.telegram_url" class="text-xs text-red-600 mt-1"></div>
    </div>

    <div class="mb-6">
      <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200" for="twitter_handle">
        X (formerly Twitter) @
      </label>
      <input v-model="form.twitter_handle"
             class="border border-gray-400 p-2 w-full rounded-lg text-black"
             type="text"
             id="twitter_handle"
      >
      <div v-if="form.errors.twitter_handle" v-text="form.errors.twitter_handle" class="text-xs text-red-600 mt-1"></div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  form: Object,
})
</script>
