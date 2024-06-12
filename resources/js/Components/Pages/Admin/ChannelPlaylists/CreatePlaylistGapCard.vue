<template>
  <div class="flex flex-col justify-center space-x-2">
    <div class="text-sm font-semibold">Gap</div>
    <div class="text-xs">
      <span class="text-xs uppercase font-semibold">Start time: </span>{{ formatDateTime(item.start_dateTime) }}
    </div>
    <div class="text-xs">
      <span class="text-xs uppercase font-semibold">End time: </span>{{ formatDateTime(item.end_dateTime) }}
    </div>
    <div class="text-xs text-gray-500">{{ item.duration_minutes }} minutes</div>
    <button @click.prevent="store.openAddContentModal()" class="btn btn-sm btn-primary mt-2">Add Content</button>
  </div>
</template>

<script setup>
import { useChannelPlaylistStore } from '@/Stores/ChannelPlaylistStore'

const store = useChannelPlaylistStore()

const props = defineProps({
  item: Object,
});

</script>
