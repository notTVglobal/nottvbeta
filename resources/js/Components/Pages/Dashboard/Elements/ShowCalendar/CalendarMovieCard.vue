<template>
  <Button @click.prevent="Inertia.visit(`movies/${media.slug}`)" class="bg-blue-500 hover:bg-indigo-500 hover:cursor-pointer text-white h-full flex flex-col p-2 rounded-lg shadow transition ease-in-out duration-150">
    <div class="flex-grow w-full">
      <!-- Movie Details -->
      <div class="w-full text-orange-500 text-xs font-semibold uppercase tracking-wider">
        <template v-if="media.category?.name">
          {{ media.category.name }}
        </template>
        <template v-else>
          <!-- Placeholder to maintain layout -->
          <span class="invisible">No Category</span>
        </template>
      </div>
      <div class="font-semibold text-sm">{{ media.name }}</div>

    </div>
                    <SingleImage
                        :image="media.image"
                        :alt="media.name"
                        class="w-full self-end rounded my-2 hover:opacity-75 transition ease-in-out duration-150" />
<!--    <img :src="media.image" alt="Movie Image" class="w-full self-end rounded my-2 hover:opacity-75 transition ease-in-out duration-150">-->
    <!-- Additional Movie Info -->

    <div class="w-full text-gray-100 hover:text-gray-800 text-xs font-semibold uppercase tracking-wider">
    <template v-if="media.category?.name">
      <div class="flex flex-row w-full justify-between">
        <div>MOVIE</div>
        <div>{{startTime}}</div>
      </div>


    </template></div>
  </Button>
</template>

<script setup>
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'
import Button from '@/Jetstream/Button.vue'
import { Inertia } from '@inertiajs/inertia'

defineProps({
  media: Object,
  startTime: String,
});
</script>
