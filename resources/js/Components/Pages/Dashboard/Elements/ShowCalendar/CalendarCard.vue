<template>
  <div class="badge badge-xs absolute -mt-2 -mr-20 justify-start z-60 font-semibold"
       :class="badgeBgColorClass">
    {{ type }}
  </div>
    <Button @click.prevent="buttonSlug" class="relative w-full bg-blue-500 hover:bg-indigo-800 hover:cursor-pointer text-white h-full flex flex-col p-2 rounded-lg shadow transition ease-in-out duration-150 z-50">
    <div class="flex-grow">
      <!-- Custom message -->
      <div v-if="isLive">
        <div v-if="isWatching" class="text-green-500">
          You're watching this now! Enjoy the show.
        </div>
        <div v-else class="text-red-500 my-2">
          Now Streaming
        </div>
      </div>
      <!-- Show Details -->
      <div class="w-full text-orange-500 text-xs font-semibold uppercase tracking-wider">

        <template v-if="content?.category?.name">
          {{ content?.category.name }}
        </template>
        <template v-else>
          <!-- Placeholder to maintain layout -->
          <span class="invisible">No Category</span>
        </template>
      </div>
      <div class="font-semibold text-sm">{{ content?.name }}</div>
    </div>
    <SingleImage
        :image="content?.image"
        :placeholder="content?.image?.placeholder"
        :alt="content?.image?.name"
        class="w-full self-end rounded my-2 hover:opacity-75 transition ease-in-out duration-150" />
    <!-- Additional Show Info -->
    <div class="w-full min-h-[20px] text-gray-100 text-xs font-semibold uppercase tracking-wider">
      <template v-if="content?.category?.name">
        <div class="flex flex-row w-full justify-center">
          <div>{{ startTime || ' ' }}</div>
        </div>


      </template></div>
  </Button>
</template>

<script setup>
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'
import { router } from '@inertiajs/vue3'
import Button from '@/Jetstream/Button.vue'
import { computed, ref } from 'vue'

// Correct object definition with placeholder
// let image = ref({
//   placeholder: "https://picsum.photos/200/300"
// });

const randomQueryParam = new Date().getTime();
let placeholder = ref(`https://picsum.photos/200/300?random=${randomQueryParam}`);


const props = defineProps({
  content: Object,
  type: String,
  startTime: String,
  isLive: Boolean,
  isWatching: {
    type: Boolean,
    default: false, // Provide a default value if appropriate
  },
});

const buttonSlug = () => {
  if( props.type === 'movie') {
    router.visit('movies/' + props.content?.slug)
  } else if (props.type === 'show') {
    router.visit('shows/' + props.content?.show?.slug + '/episode/' + props.content?.slug)
  }
}

const badgeBgColorClass = computed(() => {
  switch (props.type) {
    case 'movie':
      return 'bg-pink-500 border-pink-600 text-pink-900';
    case 'show':
      return 'bg-green-500 border-green-600 text-green-900';
    default:
      return ''; // Default case if type is not 'movie' or 'show'
  }
});
</script>
