<template>
  <div>
    <div class="flex flex-row justify-between gap-x-4 mb-3">
      <div>
        <Link :href="`#`">
          <button
              @click.prevent="openAddChannelModal"
              class="btn btn-sm bg-green-500 hover:bg-green-600 text-white px-4 py-2 mr-2 rounded disabled:bg-gray-400"
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
              @click.prevent="openAddChannelPlaylistModal"
              class="btn btn-sm bg-green-500 hover:bg-green-600 text-white px-4 py-2 mr-2 rounded disabled:bg-gray-400"
          >Add Channel Playlist
          </button>
        </Link>
        <button
            class="btn btn-sm bg-green-500 hover:bg-green-600 text-white px-4 py-2 mr-2 rounded disabled:bg-gray-400"
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

    <dialog id="adminAddChannel" class="modal">
      <div class="modal-box">
        <form method="dialog">
          <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-2">Add Channel</h3>
        <input v-model="newChannelName" type="text" placeholder="Channel Name" class="input input-bordered w-full max-w-xs" />
        <button @click.prevent="submit" class="btn btn-primary ml-2">Add</button>
      </div>
    </dialog>

    <dialog id="addChannelPlaylistModal" class="modal">
      <div class="modal-box">
        <form method="dialog">
          <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-2">Add Channel Playlist</h3>
        <CreatePlaylist />
      </div>
    </dialog>
  </div>
</template>
<script setup>
import { useAdminStore } from '@/Stores/AdminStore'
import AddOrUpdateMistStreamModal from '@/Components/Global/MistStreams/AddOrUpdateMistStreamModal'
import { ref } from 'vue'
import CreatePlaylist from '@/Components/Pages/Admin/ChannelPlaylists/CreatePlaylist.vue'

const adminStore = useAdminStore()

const newChannelName = ref('')

const openAddChannelModal = () => {
  document.getElementById('adminAddChannel').showModal()
}

const openAddChannelPlaylistModal = () => {
  document.getElementById('addChannelPlaylistModal').showModal()
}

const submit = async () => {
  await adminStore.addChannel(newChannelName.value)
  document.getElementById('adminAddChannel').close()
  newChannelName.value = ''
}
</script>