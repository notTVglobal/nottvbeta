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
    <button @click.prevent="openAddContentModal" class="btn btn-sm btn-primary mt-2">Add Content</button>
  </div>
</template>

<script setup>
const props = defineProps({
  item: Object,
});

const emit = defineEmits(['openAddContentModal']);

const openAddContentModal = () => {
  // Emit an event to open the modal
  emit('openAddContentModal', props.item);
};
</script>
