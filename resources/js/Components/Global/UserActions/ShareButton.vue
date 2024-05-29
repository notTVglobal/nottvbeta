<template>
  <div>
    <button @click="handleShare"
            class="flex bg-blue-500 text-white font-semibold px-4 py-2 hover:bg-blue-400 rounded transition ease-in-out duration-150 items-center">
      <font-awesome-icon icon="fa-share" class="mr-2"/>
      Share
    </button>


  </div>
</template>

<script setup>
import { useSocialShareStore } from '@/Stores/SocialShareStore'

const socialShareStore = useSocialShareStore()

const props = defineProps({
  model: {
    type: Object,
    required: true
  },
})

const handleShare = () => {
  socialShareStore.parseModel(props.model)
}
</script>