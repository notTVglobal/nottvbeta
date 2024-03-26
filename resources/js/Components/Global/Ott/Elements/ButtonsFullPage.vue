<template>
  <div>
    <div class="ottButtonsContainer button-custom-container" id="ottButtons">
      <div class="flex flex-row flex-wrap-reverse space-x-2 justify-center">
        <button
            v-if="appSettingStore.prevUrl"
            @click="appSettingStore.back()"
            class="ottButton bg-gray-400 text-white hover:bg-gray-600">
          <font-awesome-icon icon="fa-angle-left" class="ml-1.5 text-3xl mb-1"/>
          BACK
        </button>
        <!--            <button-->
        <!--                v-if="urlPrev !== 'empty'"-->
        <!--                @click="back"-->
        <!--                class="ottButton bg-gray-400 text-white hover:bg-gray-600 hover:text-gray-300">-->
        <!--                <font-awesome-icon icon="fa-angle-left" class="ml-2 text-3xl mb-1"/><div>BACK</div>-->
        <!--            </button>-->
        <button
            @click="appSettingStore.toggleOttChannels()"
            class="ottButton bg-green-400 text-white hover:bg-green-600">
          <font-awesome-icon icon="fa-rocket" class="ml-3.5 text-3xl mb-1"/>
          CHANNELS
        </button>

        <button
                @click="appSettingStore.toggleOttPlaylist()"
                class="ottButton bg-orange-400 text-white hover:bg-orange-600">
          <font-awesome-icon icon="fa-list" class="ml-2 text-3xl mb-1"/>
          PLAYLIST
        </button>

        <button @click="appSettingStore.toggleOttChat()"
                class="ottButton bg-blue-400 text-white hover:bg-blue-600">
          <font-awesome-icon icon="fa-comments" class="text-3xl mb-1"/>
          CHAT
        </button>

        <button v-if="userStore.isVip || userStore.isAdmin"
                @click="appSettingStore.toggleOttFilters()"
                class="ottButton bg-yellow-400 text-white hover:bg-yellow-600"
                >
          <font-awesome-icon icon="fa-filter" class="ml-1 text-3xl mb-1"/>
          FILTERS
        </button>
      </div>
    </div>
  </div>

</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import { ref, onMounted, computed } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'

const appSettingStore = useAppSettingStore()
import { useChatStore } from '@/Stores/ChatStore'
import { useUserStore } from '@/Stores/UserStore'

const videoPlayerStore = useVideoPlayerStore()
const chatStore = useChatStore()
const userStore = useUserStore()

const urlPrev = usePage().props.value.urlPrev

const previousURL = ref(null)

</script>

<style>
/* Container style */
.button-container {
  display: flex;
  flex-wrap: wrap-reverse;
  gap: 10px; /* Adjust gap between buttons as needed */
}

/* Button style */
.ottButton {
  flex: 1; /* Make buttons take equal space */
  /* Adjust minimum width for buttons to control when they wrap */
  order: 2; /* Default order to make non-first buttons appear in the first row */
}

/* First button specific style */
#first-btn {
  order: 1; /* On larger screens where no wrapping occurs, this has no effect */
}

/* Media query for smaller screens */
@media (max-width: 800px) {
  /* Adjust breakpoint as needed */
  .ottButton {
    order: 3; /* Make other buttons take precedence in the first row */
  }

  #first-btn {
    order: 0; /* Ensure the first button moves to the start of the second row when wrapped */
  }
}
</style>
