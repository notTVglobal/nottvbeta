<template>
  <div>
    <dialog :id="id" class="modal">
      <div class="modal-box text-center my-auto border-2 border-secondary" data-theme="dark">
        <h2 class="font-bold text-3xl text-secondary">
          <slot name="header"></slot>
        </h2>
        <p class="py-4 text-xl">
          <slot name="main"></slot>
        </p>
        <div class="modal-action justify-center w-full">
          <form method="dialog">
            <!-- if there is a button in form, it will close the modal -->
            <button @click="notificationStore.showPopUpModal = false" class="btn btn-secondary">Okay</button>
          </form>
        </div>
      </div>
    </dialog>
  </div>
</template>
<script setup>
import { useNotificationStore } from "@/Stores/NotificationStore"

const notificationStore = useNotificationStore()

defineProps({
  id: String,
})
</script>
