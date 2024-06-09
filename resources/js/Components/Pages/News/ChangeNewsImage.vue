<template>
  <div>
    <div class="w-full flex justify-between gap-2">
      <SingleImage :image="newsStore.image" :alt="`image`" :class="`skeleton max-w-full max-h-20 object-contain`"/>
      <button v-if="newsStore.image" @click="removeImage" class="btn btn-danger mt-2">Remove Image</button>
    </div>
    <ImageUpload :image="newsStore.image"
                 :modelType="'newsStory'"
                 :modelId="`${newsStore.id}`"
                 :name="'Upload News Image'"
                 :maxSize="'30MB'"
                 :fileTypes="'image/jpg, image/jpeg, image/png'"
                 @imageUploaded="updateImage"
                 :key="newsStore.image"
    />
  </div>
</template>
<script setup>
import { useNewsStore } from '@/Stores/NewsStore'
import { useNotificationStore } from '@/Stores/NotificationStore'
import ImageUpload from '@/Components/Global/Uploaders/ImageUpload.vue'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'

const newsStore = useNewsStore()
const notificationStore = useNotificationStore()

const updateImage = (newImageUrl) => {
  console.log(newImageUrl)
  newsStore.image = newImageUrl
}

const removeImage = async () => {
  try {
    await axios.post('/api/remove-image', {
      modelId: newsStore.id,
      modelType: 'newsStory',
      imageId: newsStore.image.id,
    })
    newsStore.image = null
    notificationStore.setToastNotification('Image removed.', 'info')
  } catch (error) {
    console.error('Error removing image:', error)
  }
}

</script>