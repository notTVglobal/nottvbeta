<template>
  <div>
    <div class="w-full mr-4 relative">
      <input
          v-model="searchInput"
          type="search"
          class="w-full rounded-lg mt-2 bg-white text-black p-2"
          placeholder="Search..."
          @focus="shopStore.favouriteSelectDropdownVisible = true"
          @blur="handleInputBlur"
          @keydown="handleKeydown"
      />

      <ul
          v-if="shopStore.favouriteSelectDropdownVisible && filteredFavouriteOptions.length > 0"
          ref="scrollableContainer"
          class="absolute z-10 w-full bg-white text-black text-left mt-1 max-h-50 rounded-lg shadow-lg overflow-auto border border-gray-200"
      >
        <li
            v-for="(item, index) in filteredFavouriteOptions"
            :key="index"
            class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-gray-100"
            :class="{
            'bg-gray-200': index === shopStore.focusedIndex,
            'dropdown-item': true,
            [`dropdown-item-${index}`]: true
          }"
            @click="selectFavourite(item)"
        >
          <div class="flex items-center gap-2">
            <FavouriteSelectedImage :item="item" />
            {{ item.name }}
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useShopStore } from '@/Stores/ShopStore'
import FavouriteSelectedImage from '@/Components/Pages/Shop/FavouriteSelectedImage.vue'

const shopStore = useShopStore()
const searchInput = ref('')

const filteredFavouriteOptions = computed(() => {
  if (!searchInput.value) {
    return shopStore.selectedFavouriteOptions
  }
  return shopStore.selectedFavouriteOptions.filter(option =>
      option.name.toLowerCase().includes(searchInput.value.toLowerCase()),
  )
})

const handleInputBlur = () => {
  setTimeout(() => {
    shopStore.favouriteSelectDropdownVisible = false
  }, 200)
}

const handleKeydown = (event) => {
  const key = event.key
  const length = filteredFavouriteOptions.value.length

  if (key === 'ArrowDown') {
    shopStore.focusedIndex = (shopStore.focusedIndex + 1) % length
  } else if (key === 'ArrowUp') {
    shopStore.focusedIndex = (shopStore.focusedIndex + length - 1) % length
  } else if (key === 'Enter') {
    const selectedItem = filteredFavouriteOptions.value[shopStore.focusedIndex]
    if (selectedItem) {
      selectFavourite(selectedItem)
    }
  }
}

const selectFavourite = (item) => {
  shopStore.selectedFavourite = item
  searchInput.value = item.name
  shopStore.favouriteSelectDropdownVisible = false
}
</script>
