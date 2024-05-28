<template>
  <div>
    <div class="flex space-y-3">
      <div class="mb-6">
        <SingleImage :image="image" :key="image" :alt="'show poster'" class=""/>
      </div>
    </div>

    <div class="w-full">

      <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
             for="name"
      >
        Change Show Poster
      </label>

      <ImageUpload :image="image"
                   :name="'Upload Show Poster'"
                   :modelType="'show'"
                   :modelId="`${show.id}`"
                   :maxSize="'30MB'"
                   :fileTypes="'image/jpg, image/jpeg, image/png'"
                   @reloadImage="handleReloadImage"
      />

    </div>

  </div>
</template>
<script setup>
import SingleImage from '@/Components/Global/Multimedia/SingleImage'
import ImageUpload from '@/Components/Global/Uploaders/ImageUpload'

const props = defineProps({
  image: Object,
  show: Object,
})

const emit = defineEmits(['reloadImage'])

const handleReloadImage = () => {
  emit('reloadImage')
}
</script>