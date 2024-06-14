<template>
  <div class="flex flex-col p-2 border-b border-gray-200 space-y-2 bg-gray-100">
    <div class="flex gap-2 opacity-50">
      <SingleImage :image="item?.content?.image" :class="`w-8 h-8 object-contain`" />
      <div class="flex flex-col">
        <div class="text-sm font-semibold">{{ item.content.name }}</div>
        <div class="text-xs"><span class="text-xs uppercase font-semibold">Start time: </span>{{ formatDateTime(item.start_dateTime) }}</div>
        <div class="text-xs text-gray-500">{{ item.duration_minutes }} minutes</div>
      </div>
    </div>

    <div class="flex flex-wrap justify-between">
      <div>
        <span class="badge badge-outline opacity-50">{{ item.type }}</span>
        <span v-if="item.is_scheduled" class="badge badge-outline text-black ml-2 opacity-50">Scheduled</span>
      </div>
      <button @click.prevent="$emit('addItem', item.id)" class="btn btn-xs btn-success">Add</button>
    </div>
  </div>
</template>

<script setup>
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'

const props = defineProps({
  item: Object,
  index: Number,
});

const emit = defineEmits(['addItem']);
</script>
