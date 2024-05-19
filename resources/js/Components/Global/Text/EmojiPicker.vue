<template>
  <div v-if="showPicker" :class="emojiPickerClass">
    <div class="cursor-pointer transition-all duration-200 text-2xl transform hover:scale-150 m-1"
         v-for="emoji in emojis"
         :key="emoji"
         @click="selectEmoji(emoji)">
      {{ emoji }}
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useAppSettingStore} from '@/Stores/AppSettingStore'

const appSettingStore = useAppSettingStore()

const props = defineProps({
  showPicker: Boolean,
})

const emits = defineEmits(['select'])

const emojis = [
  '😀', '😂', '😍', '😎', '😭', '😡', '👍', '❤️', '🔥', '🎉', '🙌', '🥳', '🤔', '😇', '🤩', '😜',
  '✨', '🚀', '🎈', '🌟', '💡', '🦄', '🍀', '🌈', '🌻', '🌞'
];

const selectEmoji = (emoji) => {
  emits('select', emoji)
}

const emojiPickerClass = computed(() => {
  return [
    'fixed h-32 right-2 bg-white border border-gray-300 shadow-md flex flex-wrap p-2 z-50 rounded-lg',
    appSettingStore.fullPage ? 'bottom-32 w-96' : 'bottom-24 w-96'
  ]
})
</script>

<style scoped>
.emoji-picker {
  position: absolute;
  height: 8rem;
  bottom: 5rem;
  right: 0.5rem;
  background: white;
  border: 1px solid #ddd;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
  display: flex;
  flex-wrap: wrap;
  padding: 10px;
  z-index: 1000;
  border-radius: 10px;
}
</style>
