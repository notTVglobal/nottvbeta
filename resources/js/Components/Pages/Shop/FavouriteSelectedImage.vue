<template>
  <div :ref="el => setTargetRef(el, item.id)">
    <div v-if="shopStore.selectedFavouriteType === 'show'">
      <SingleImage
          v-if="isVisible(item.id)"
          :image="item.image"
          :alt="``"
          :class="`skeleton h-10 w-10 rounded-full object-cover`"/>
    </div>
    <div v-else>
      <img
          v-if="item.profile_photo_path && isVisible(item.id)"
          :src="'/storage/' + item.profile_photo_path"
          :alt="item.name + ' profile photo'"
          class="h-10 w-10 rounded-full object-cover">
      <img
          v-else-if="item.profile_photo_url && isVisible(item.id)"
          :src="item.profile_photo_url"
          alt="creator.name"
          class="h-10 w-10 rounded-full object-cover">
      <img
          v-else-if="isVisible(item.id)"
          src="/storage/images/Ping.png"
          alt="no profile photo, using our ping logo as a placeholder"
          class="h-10 w-10 rounded-full object-cover">
    </div>
  </div>
</template>
<script setup>
import { ref } from 'vue'
import { useShopStore } from '@/Stores/ShopStore'
import { useIntersectionObserver } from '@vueuse/core'

const shopStore = useShopStore()
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'

const props = defineProps({
  item: Object,
})

const scrollableContainer = ref(null)
const visibilityMap = ref({})

const isVisible = (id) => visibilityMap.value[id] ?? false

const setTargetRef = (el, id) => {
  if (el) {
    useIntersectionObserver(
        el,
        ([{ isIntersecting }]) => {
          if (isIntersecting) {
            visibilityMap.value[id] = true
          }
        },
        {
          root: scrollableContainer.value,
          threshold: 0.1
        }
    )
  }
}

</script>