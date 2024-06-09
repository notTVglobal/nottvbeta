<template>

  <Head :title="`Manage Episode: ${props.episode.name}`"/>

  <div id="topDiv" class="place-self-center flex flex-col gap-y-3">
    <div class="bg-white rounded text-black p-5"
         :class="goLive">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <ShowEpisodeManageNoticeModals/>

      <ShowEpisodeManageHeader
          :episode="episode"
          :episodeStatus="episodeStatus"
          :show="show"
          :team="team"
      />
<!--      <GoLive :episode="episode" :scheduledDateTime="scheduledDateTime"/>-->
      <GoLive v-if="goLiveStore.displayEpisodeGoLiveComponent" />

      <EpisodeNotes :episode="episode"/>

      <ShowEpisodeManageBonusContent/>

      <ShowEpisodeManageEpisodeDescription :episode="episode"/>

      <EpisodeRundown />

      <EpisodeVideo :episode="episode"/>

      <EpisodeFooter :can="can" :team="team" :episode="episode" :show="show"/>

    </div>
  </div>

</template>

<script setup>
import { computed, onMounted, onUnmounted, watch } from 'vue'
import { useTimeAgo } from '@vueuse/core'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useGoLiveStore } from '@/Stores/GoLiveStore'
import { useShowStore } from '@/Stores/ShowStore'
import { useShowEpisodeStore } from '@/Stores/ShowEpisodeStore'
import { useTeamStore } from '@/Stores/TeamStore'

import ShowEpisodeManageEpisodeDescription from '@/Components/Pages/ShowEpisodes/Elements/ManageShowEpisodeDescription'
import ShowEpisodeManageBonusContent from '@/Components/Pages/ShowEpisodes/Elements/ManageShowEpisodeBonusContent'
import ShowEpisodeManageNoticeModals from '@/Components/Pages/ShowEpisodes/Elements/ManageShowEpisodeNoticeModals'
import ShowEpisodeManageHeader from '@/Components/Pages/ShowEpisodes/Layout/ManageShowEpisodeHeader'
import EpisodeRundown from '@/Components/Pages/ShowEpisodes/Elements/ManageShowEpisodeRundown'
import EpisodeNotes from '@/Components/Pages/ShowEpisodes/Elements/ManageShowEpisodeNotes'
// import GoLive from '@/Components/Pages/ShowEpisodes/Elements/ManageShowEpisodeGoLive'
import GoLive from '@/Components/Global/GoLive/GoLive'
import EpisodeVideo from '@/Components/Pages/ShowEpisodes/Elements/EpisodeVideo'
import EpisodeFooter from '@/Components/Pages/ShowEpisodes/Layout/EpisodeFooter'
import Message from '@/Components/Global/Modals/Messages'

usePageSetup('showEpisodesManage')

const appSettingStore = useAppSettingStore()
const goLiveStore = useGoLiveStore()
const showStore = useShowStore()
const showEpisodeStore = useShowEpisodeStore()
const teamStore = useTeamStore()

let props = defineProps({
  show: Object,
  team: Object,
  episode: Object,
  episodeStatus: Object,
  can: Object,
})

goLiveStore.updateEpisode(props.episode);

// const timeAgo = useTimeAgo(new Date(2023, 10, 5))
const timeAgo = useTimeAgo(props.scheduledDateTime)

teamStore.can = props.can

// let showGoLive = ref(false)

const goLive = computed(() => ({
  'border-4 border-red-500': teamStore.goLiveDisplay,
}))

onMounted(() => {
  showEpisodeStore.initializeShowEpisode(props.episode, props.show, props.team)
})



</script>


