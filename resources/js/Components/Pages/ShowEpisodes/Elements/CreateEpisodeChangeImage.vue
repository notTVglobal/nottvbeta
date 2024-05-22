<template>
  <div class="mt-6">
    <label class="block my-2 uppercase font-bold text-xs text-red-700"
           for="name"
    >

    </label>
    <div class="max-full mx-auto mt-2 mb-6 bg-gray-200 p-6">

      <ImageUpload :image="image"
                   :modelType="'showEpisode'"
                   :modelId="showEpisodeStore.episode.id"
                   :name="'Upload Episode Poster'"
                   :maxSize="'20MB'"
                   :fileTypes="'image/jpg, image/jpeg, image/png'"
                   @reloadImage="handleReloadImage"
      />

      <div class="flex space-y-3">
        <div class="mb-6">
          <SingleImage :image="image" :key="image"/>
        </div>
      </div>

    </div>

  </div>
</template>
<script setup>
import { useShowEpisodeStore } from '@/Stores/ShowEpisodeStore'
import ImageUpload from '@/Components/Global/Uploaders/ImageUpload'
import SingleImage from '@/Components/Global/Multimedia/SingleImage'

const showEpisodeStore = useShowEpisodeStore()

const props = defineProps({
  image: Object,
  errors: Object,
})

const emit = defineEmits(['reloadImage'])

const handleReloadImage = () => {
  emit('reloadImage')
}

</script>