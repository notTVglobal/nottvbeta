<template>
  <div>
    <div class="mt-6 pt-3 border-t">
      <h3 class="text-lg font-bold mb-2">Schedule Items</h3>
      <div class="flex flex-wrap gap-2">
        <div class="mb-4" v-if="store.hasRemovedItems">
          <button @click.prevent="clearRemovedItems" class="btn btn-sm btn-danger">Clear All Removed Items</button>
        </div>
        <div class="mb-4" v-if="store.conflictCount > 0">
          <button @click.prevent="resolveConflicts" class="btn btn-sm btn-warning">Resolve Conflicts</button>
        </div>
        <div class="mb-4" v-if="store.conflictCount === 0 && store.gapCount > 0">
          <button @click.prevent="store.insertGaps()" class="btn btn-sm btn-danger">Insert Gaps</button>
        </div>
        <div class="mb-4" v-if="store.scheduleItems.length > 0">
          <button @click.prevent="store.removeAllItems()" class="btn btn-sm btn-danger">Remove All Items</button>
        </div>
      </div>
      <ul :class="isSmallScreen ? 'space-y-4' : 'divide-y divide-gray-200 space-y-4'">
        <li
            v-for="(item, index) in store.scheduleItemsWithUserTimezone"
            :key="item.id"
            :class="getListItemClass(item.conflict, item.removed)"
        >
          <CreatePlaylistItemCard v-if="!item.removed && item.type !== 'gap'" :item="item" :index="index"
                                  @removeItem="removeItem"/>
          <CreatePlaylistGapCard v-if="item.type === 'gap'" :item="item"
                                 @openAddContentModal="openAddContentModal"/>
          <CreatePlaylistRemovedCard v-if="item.removed" :item="item" :index="index" @addItem="addItem"/>
        </li>
      </ul>
    </div>
    <div class="sticky bottom-0 left-0 w-full p-4 bg-white border-t border-gray-200">
      <p v-if="error" class="py-3 text-red-500">{{ error }}</p>
      <div class="flex justify-between">
        <button type="submit" class="btn bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">{{ submitButtonText }}
        </button>
        <button @click.prevent="useSchedule"
                class="btn bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
          <span v-if="store.loadingSchedules" class="loading loading-spinner"/>
          <span v-else>Use Schedule</span>
        </button>
      </div>
      <div v-if="store.conflictCount > 0 || store.gapCount > 0" class="mt-2">
        <span class="text-sm text-red-600 font-semibold">Conflicts: {{ store.conflictCount }}</span>
        <span class="text-sm text-orange-600 font-semibold ml-4">Gaps: {{ store.gapCount }}</span>
        <span v-if="store.processing" class="ml-6 text-sm text-gray-600 font-semibold">Processing <span
            class="loading loading-dots text-info"></span> Please wait</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { usePlaylistForm } from '@/Mixins/usePlaylistForm'
import CreatePlaylistItemCard from '@/Components/Pages/Admin/ChannelPlaylists/CreatePlaylistItemCard.vue'
import CreatePlaylistGapCard from '@/Components/Pages/Admin/ChannelPlaylists/CreatePlaylistGapCard.vue'
import CreatePlaylistRemovedCard from '@/Components/Pages/Admin/ChannelPlaylists/CreatePlaylistRemovedCard.vue'

const props = defineProps({
  submitButtonText: {
    type: String,
    required: true
  }
})

const {
  store,
  isSmallScreen,
  error,
  getListItemClass,
  clearRemovedItems,
  resolveConflicts,
  removeItem,
  addItem,
  openAddContentModal,
  useSchedule
} = usePlaylistForm()
</script>
