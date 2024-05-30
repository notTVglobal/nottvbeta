<template>
  <div>
    <div class="w-full mr-4 relative">
      <input
          v-model="searchInput"
          type="search"
          class="w-full rounded-lg mt-2 bg-white text-black p-2"
          placeholder="Search..."
          @focus="dropdownVisible = true"
          @blur="handleInputBlur"
          @keydown="handleKeydown"
      />
      <ul
          v-if="dropdownVisible && filteredNewsPersons.length > 0"
          ref="scrollableContainer"
          class="absolute z-10 w-full bg-white text-black text-left mt-1 max-h-50 rounded-lg shadow-lg overflow-auto border border-gray-200"
      >
        <li
            v-for="(item, index) in filteredNewsPersons"
            :key="index"
            class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-gray-100"
            :class="{
            'bg-gray-200': index === focusedIndex,
            'dropdown-item': true,
            [`dropdown-item-${index}`]: true
          }"
            @click="selectNewsPerson(item)"
        >
          <div class="flex items-center gap-2">
            <SingleImage :image="item.image" :alt="`NewsPerson Image`" :class="`w-8 h-8 rounded-full`"/>
            {{ item.name }}
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useNewsPersonMessageStore } from '@/Stores/NewsPersonMessageStore'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'

const newsPersonMessageStore = useNewsPersonMessageStore()

const searchInput = ref('')

const filteredNewsPersons = computed(() => newsPersonMessageStore.filteredNewsPersons)

const dropdownVisible = ref(false)
const focusedIndex = ref(-1)

const emit = defineEmits(['select'])

const handleInputBlur = () => {
  setTimeout(() => {
    dropdownVisible.value = false
  }, 200)
}

const handleKeydown = (event) => {
  switch (event.key) {
    case 'ArrowDown':
      if (focusedIndex.value < filteredNewsPersons.value.length - 1) {
        focusedIndex.value++
      }
      break
    case 'ArrowUp':
      if (focusedIndex.value > 0) {
        focusedIndex.value--
      }
      break
    case 'Enter':
      if (focusedIndex.value >= 0 && focusedIndex.value < filteredNewsPersons.value.length) {
        selectNewsPerson(filteredNewsPersons.value[focusedIndex.value])
      }
      break
  }
}

const selectNewsPerson = (person) => {
  searchInput.value = person.name // Set the input value to the selected person's name
  newsPersonMessageStore.setSearchInput(searchInput.value)
  dropdownVisible.value = false // Hide the dropdown
  emit('select', person.id ) // Emit the selected person to the parent component
}

onMounted(() => {
  newsPersonMessageStore.fetchNewsPersons()
})

watch(searchInput, (newVal) => {
  newsPersonMessageStore.setSearchInput(newVal)
})
</script>
