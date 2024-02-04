<template>
  <div>
    <div class="ottButtonsContainer" id="ottButtons">

      <button
          v-if="appSettingStore.prevUrl"
          @click="back"
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
          v-if="userStore.isSubscriber || userStore.isVip || userStore.isAdmin"
          @click="appSettingStore.toggleOttChannels()"
          class="ottButton bg-green-400 text-white hover:bg-green-600">
        <font-awesome-icon icon="fa-rocket" class="ml-3.5 text-3xl mb-1"/>
        CHANNELS
      </button>

      <button v-if="userStore.isVip || userStore.isAdmin"
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
              class="ottButton bg-yellow-400 text-white hover:bg-yellow-600">
        <font-awesome-icon icon="fa-filter" class="ml-1 text-3xl mb-1"/>
        FILTERS
      </button>
    </div>
  </div>

</template>

<script setup>
import { Inertia } from "@inertiajs/inertia"
import { ref, onMounted, computed } from "vue"
import { usePage } from "@inertiajs/inertia-vue3"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()
import { useChatStore } from "@/Stores/ChatStore"
import { useUserStore } from "@/Stores/UserStore"

const videoPlayerStore = useVideoPlayerStore()
const chatStore = useChatStore()
const userStore = useUserStore()

const urlPrev = usePage().props.value.urlPrev

const previousURL = ref(null);

onMounted(() => {

});

function back() {
  Inertia.visit(appSettingStore.prevUrl)
}
</script>
