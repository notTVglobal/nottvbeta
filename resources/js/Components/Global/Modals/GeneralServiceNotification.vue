<template>

<div v-show="notificationStore.showGeneralServiceNotification">

  <dialog id="generalServiceNotificationModal" class="modal">
    <div v-if="notificationStore.generalServiceNotification" class="modal-box text-black bg-white dark:bg-gray-800 dark:text-white">
      <h3 class="font-bold text-lg">{{ notificationStore.generalServiceNotification.title }}</h3>
      <p class="py-4">{{ notificationStore.generalServiceNotification.body }}</p>
      <div class="modal-action">
        <label class="btn" @click="closeModal">Close</label>
      </div>
    </div>

  </dialog>
</div>
</template>

<script setup>
import { useNotificationStore } from "@/Stores/NotificationStore"

const notificationStore = useNotificationStore()

const closeModal = () => {
  const modal = document.getElementById('generalServiceNotificationModal')
  if (modal && modal.close) {
    modal.close()
    notificationStore.clearGeneralServiceNotification()
  }
};

</script>
