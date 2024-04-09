<template>

  <Teleport to="body">
    <Modal :show="appSettingStore.showConfirmationDialog" @close="appSettingStore.showConfirmationDialog = false">

      <template #header>
        Publish {{newsStory.title}}
      </template>


      <template #default>

        <div class="space-y-3">
          <div class="w-full text-center text-2xl text-red-700 font-bold">
            Are you sure?
          </div>
        </div>

        <div class="w-full text-center pt-3 font-semibold">You will not be able to edit the story after publishing.</div>
        <div class="w-full text-center pt-1 font-semibold text-red-700 italic">This action cannot be undone.</div>
      </template>
      <template #footer>
        <div class="w-full flex justify-center">
          <button @click.prevent="publishConfirmed" class="bg-green-500 hover:bg-green-600 py-2 px-4 text-white rounded-lg">
            Publish
          </button>
          <button @click.prevent="appSettingStore.showConfirmationDialog = false"
                  class="bg-gray-500 hover:bg-gray-600 py-2 px-4 text-white rounded-lg ml-2">Cancel
          </button>
        </div>
      </template>
    </Modal>
  </Teleport>


</template>

<script setup>
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import Modal from "@/Components/Global/Modals/Modal"

const appSettingStore = useAppSettingStore()

defineProps({
  newsStory: Object,
})

const emit = defineEmits(['confirmPublish'])

function publishConfirmed() {
  emit('confirmPublish')
  appSettingStore.showConfirmationDialog = false
}

</script>
