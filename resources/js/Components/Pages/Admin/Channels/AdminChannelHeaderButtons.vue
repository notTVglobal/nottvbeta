<template>
  <div class="flex flex-row justify-between gap-x-4 mb-3">
    <div>
      <Link :href="`#`">
        <button
            class="btn btn-sm bg-green-500 hover:bg-green-600 text-white px-4 py-2 mr-2 rounded disabled:bg-gray-400"
            disabled
        >Add Channel
        </button>
      </Link>
      <Link :href="`#`">
        <button
            class="btn btn-sm bg-green-500 hover:bg-green-600 text-white px-4 py-2 mr-2 rounded disabled:bg-gray-400"
            disabled
        >Add External Source
        </button>
      </Link>
      <Link :href="`#`">
        <button
            class="btn btn-sm bg-green-500 hover:bg-green-600 text-white px-4 py-2 mr-2 rounded disabled:bg-gray-400"
            disabled
        >Add Channel Playlist
        </button>
      </Link>
      <button
          class="btn btn-sm bg-green-500 hover:bg-green-600 text-white px-4 py-2 mr-2 rounded-lg disabled:bg-gray-400"
          onclick="addMistStreamModal.showModal()">
        Add Mist Stream
      </button>
      <AddOrUpdateMistStreamModal :id="`addMistStreamModal`" :form-errors="$page.props.errors">
        <template #form-title>
          Add Mist Stream
        </template>
        <template #form-description>
          Add a new stream to the Mist Server
        </template>
        <template #button-label>
          Add
        </template>
      </AddOrUpdateMistStreamModal>

    </div>
    <input v-model="adminStore.searchTerm" type="search" placeholder="Search..." class="border px-2 rounded-lg"/>
  </div>
</template>
<script setup>
import { useAdminStore } from '@/Stores/AdminStore'
import AddOrUpdateMistStreamModal from '@/Components/Global/MistStreams/AddOrUpdateMistStreamModal'
const adminStore = useAdminStore()
</script>