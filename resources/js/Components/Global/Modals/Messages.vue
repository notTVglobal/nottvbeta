<template>
  <div class="mx-4 my-4">
    <div class="alert alert-info mt-4"
         v-if="props.flash.message">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
      <span>{{ props.flash.message }}</span>
      <button class="text-xs ml-12" @click="clearFlashMessage"> Close</button>
    </div>
    <div class="alert alert-success mt-4 mx-3"
         v-if="props.flash.success">
      <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <span>{{ props.flash.success }}</span>
      <button class="text-xs ml-12" @click="clearFlashMessage"> Close</button>
    </div>
    <div class="alert alert-warning mt-4 mx-3"
         v-if="props.flash.warning">
      <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
      </svg>
      <span>{{ props.flash.warning }}</span>
      <button class="text-xs ml-12" @click="clearFlashMessage"> Close</button>
    </div>
    <div class="alert alert-error mt-4 mx-3"
         v-if="props.flash.error">
      <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <span>{{ props.flash.error }}</span>
      <button class="text-xs ml-12" @click="clearFlashMessage"> Close</button>
    </div>
  </div>

</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { computed } from "vue"
import { useAppSettingStore } from "@/Stores/AppSettingStore"

const appSettingStore = useAppSettingStore()

appSettingStore.showFlashMessage = true

let props = defineProps({
  flash: Object,

})

const messageType = computed(() => ({
  'text-green-700 bg-green-100 dark:bg-green-200 dark:text-green-800': props.flash.success,
  'text-blue-700 bg-blue-100 dark:bg-blue-200 dark:text-blue-800': props.flash.message,
  'text-orange-700 bg-orange-100 dark:bg-orange-200 dark:text-orange-800': props.flash.warning,
  'text-red-700 bg-red-100 dark:bg-red-200 dark:text-red-800': props.flash.error,
}))

const clearFlashMessage = async () => {
  await router.post(route('flash.clear'));
  router.reload();
};
</script>
