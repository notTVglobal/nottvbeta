<template>
  <transition name="fade">
    <div v-if="notificationStore.toastNotificationVisible" class="toast toast-start specialZindex">
      <div :class="messageClass">
        <span>{{ notificationStore.toastNotificationMessage }}</span>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { computed, watch } from 'vue'
import { useNotificationStore } from '@/Stores/NotificationStore'

const notificationStore = useNotificationStore()

const messageClass = computed(() => ({
  'alert alert-success': notificationStore.toastNotificationStatus === 'success',
  'alert alert-error': notificationStore.toastNotificationStatus === 'error',
  'alert alert-info': notificationStore.toastNotificationStatus === 'info',
  'alert alert-warning': notificationStore.toastNotificationStatus === 'warning',
}))

watch(() => notificationStore.toastNotificationVisible, (newValue) => {
  if (newValue) {
    setTimeout(() => {
      notificationStore.resetToastNotification()
    }, notificationStore.toastNotificationTimeout) // Adjust the time as needed
  }
})

</script>

<style>
.specialZindex {
  z-index: 9000
}
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */
{
  opacity: 0;
}
</style>
