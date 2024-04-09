<template>
  <AdminChannelPopperContent>
    <template #activator>
      <button @click="emit('open-modal')" :class="buttonClass">
        {{ buttonText }}
      </button>
    </template>
    <template #content>
      <div class="text-xs p-2" id="tooltip">
        <div>ID: {{ dataPart.id }}</div>
        <div>Name: {{ dataPart.name }}</div>
        <div>Path: {{ dataPart.path }}</div>
        <div>Type: {{ dataPart.type }}</div>
        <div>Provider: {{ dataPart.provider }}</div>
      </div>
    </template>
  </AdminChannelPopperContent>
</template>
<script setup>
import AdminChannelPopperContent from '@/Components/Pages/Admin/Channels/AdminChannelPopperContent'
import { computed } from 'vue'

const props = defineProps({
  data: Object,
  dataType: String,
  dataTypeDb: String,
})

const emit = defineEmits(['open-modal'])

// Computed property to dynamically generate the button text
const buttonText = computed(() => {
  return props.data[props.dataTypeDb]?.name || `No ${props.dataType} Name`
})

// Computed property for accessing the relevant data part
const dataPart = computed(() => {
  return props.data[props.dataTypeDb] || {};
})

const buttonClass = computed(() => {
  return {
    'cursor-pointer': true,
    'text-black': true,
    // Add 'font-semibold' only if data.playback_priority_type equals dataType
    'font-semibold': props.data.playback_priority_type === props.dataType
  }
})
</script>