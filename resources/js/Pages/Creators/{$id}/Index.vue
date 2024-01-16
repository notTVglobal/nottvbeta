<template>

  <Head :title="props.creator.name"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black p-5 mb-10">

      <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

      <div class="flex justify-between mb-6">
        <h1 class="text-2xl pb-3">{{ props.creator.name }}</h1>
        <Link href="/shows" class="text-blue-500 text-sm ml-2">Go back</Link>
      </div>
      <p>
        <img :src="props.creator.profile_photo_url"/>
      </p>

    </div>
  </div>

</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()
import { useStreamStore } from "@/Stores/StreamStore"
import { useUserStore } from "@/Stores/UserStore"
import Message from "@/Components/Global/Modals/Messages"

const videoPlayerStore = useVideoPlayerStore()
const streamStore = useStreamStore()
const userStore = useUserStore()

let props = defineProps({
  creator: Object,
});

userStore.currentPage = 'creator'
userStore.showFlashMessage = true

onMounted(() => {
  videoPlayerStore.makeVideoTopRight()
  if (userStore.isMobile) {

    appSettingStore.ott = 0
appSettingStore.pageIsHidden = false
  }
  document.getElementById("topDiv").scrollIntoView()
});

</script>
