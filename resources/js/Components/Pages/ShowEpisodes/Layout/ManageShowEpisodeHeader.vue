<template>
  <div>
    <ShowEpisodeManageTopBanner :episode="episode" :episodeStatus="episodeStatus"/>

    <header class="bg-gradient-to-r from-green-100 via-white to-transparent p-4 text-black rounded-lg">
      <div class="flex justify-between my-3">
        <div class="gap-2">
          <div class="font-bold mb-4 text-black">MANAGE EPISODE</div>
        </div>
        <div class="flex flex-col">
            <div class="flex flex-row w-full justify-end mb-2">
              <button
                  @click="appSettingStore.btnRedirect(`/teams/${team.slug}/manage`)"
                  class="px-4 py-2 mr-2 mb-2 h-fit text-white font-semibold bg-orange-500 hover:bg-orange-600 rounded-lg"
              >
                <font-awesome-icon icon="fa-users-cog" class="hover:text-blue-800 mr-2"/> Manage Team
              </button>
              <button
                  :disabled="goLiveStore.displayEpisodeGoLiveComponent"
                  @click="appSettingStore.btnRedirect(`/shows/${show.slug}/manage`)"
                  class="px-4 py-2 mb-2 text-white font-semibold bg-orange-600 hover:bg-orange-500 rounded-lg disabled:bg-gray-400"
              >
                <font-awesome-icon icon="fa-tv" class="hover:text-blue-800 mr-2"/> Manage Show
              </button>
            </div>
            <div class="flex flex-wrap-reverse justify-end gap-2">
              <div class="" v-if="teamStore.can.goLive && !episode.video_file_url">
                <button
                    v-if="!goLiveStore.displayEpisodeGoLiveComponent"
                    @click="goLiveStore.toggleDisplayEpisodeGoLiveComponent(episode)"
                    :disabled="episode.show_episode_status_id > 6"
                    class="hidden px-4 py-2 text-white bg-red-600 hover:bg-red-500 rounded-lg disabled:bg-gray-400"
                >
                  <font-awesome-icon icon="fa-broadcast-tower" class="hover:text-blue-800 mr-1"/> Go Live
                </button>
                <button
                    v-if="goLiveStore.displayEpisodeGoLiveComponent"
                    @click="goLiveStore.toggleDisplayEpisodeGoLiveComponent()"
                    class="px-4 py-2 text-white bg-red-600 hover:bg-red-500 rounded-lg disabled:bg-gray-400"
                >
                  <font-awesome-icon icon="fa-times" class="hover:text-blue-800 mr-1"/> Cancel
                </button>
              </div>
              <div>
                <button
                    @click="appSettingStore.btnRedirect(`/shows/${show.slug}/episode/${episode.slug}`)"
                    :disabled="goLiveStore.displayEpisodeGoLiveComponent"
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg disabled:bg-gray-400"
                >
                  <font-awesome-icon icon="fa-eye"  class="hover:text-blue-800 mr-1"/> View Episode
                </button>
              </div>
              <div>
                <button
                    v-if="teamStore.can.editEpisode"
                    @click="appSettingStore.btnRedirect(`/shows/${show.slug}/episode/${episode.slug}/edit`)"
                    :disabled="goLiveStore.displayEpisodeGoLiveComponent"
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg disabled:bg-gray-400"
                >
                  <font-awesome-icon icon="fa-pen" class="hover:text-blue-800 mr-1"/> Edit Episode
                </button>
              </div>
              <div>
                <button
                    :disabled="goLiveStore.displayEpisodeGoLiveComponent"
                    @click="appSettingStore.btnRedirect('/dashboard')"
                    class="bg-black hover:bg-gray-800 text-white font-semibold px-4 py-2 rounded-lg disabled:bg-gray-400"
                >
                  <font-awesome-icon :icon="['fas', 'tachometer-alt']" class="hover:text-blue-800 mr-1" /> Dashboard
                </button>

              </div>
          </div>
          <div class="flex w-full justify-end gap-x-2">
            <div>
              <button
                  hidden
                  v-if="!episode.video_file_url"
                  @click="appSettingStore.btnRedirect(`/shows/${show.slug}/episode/${episode.slug}/upload`)"
                  :disabled="goLiveStore.displayEpisodeGoLiveComponent"
                  class="px-4 py-2 text-white font-semibold bg-orange-600 hover:bg-orange-500 rounded-lg disabled:bg-gray-400"
              >Upload Video
              </button>
            </div>

          </div>
            </div>
          </div>


      <div class="flex justify-between flex-wrap mt-2 align-top">
        <div class="min-w-[12rem]">
          <EpisodeHeader
              :episode="episode"
              :show="show"
              :team="team"
          />
        </div>

        <div class="flex flex-row flex-wrap justify-between w-full">

          <div class="flex flex-col pt-6">

            <div><span class="text-xs capitalize font-semibold">Show: </span>
              <button :disabled="goLiveStore.displayEpisodeGoLiveComponent" @click="appSettingStore.btnRedirect(`/shows/${show.slug}/manage`)"
                      class="hover:text-blue-700 text-blue-500 ml-2 uppercase disabled:text-black">
                {{ show.name }}
              </button>
            </div>
            <div><span class="text-xs capitalize font-semibold mr-2">Show Runner: </span>
              {{ show.showRunner.name }}
            </div>

            <div>
              <span class="text-xs capitalize font-semibold mr-2">Episode Number: </span>
              <span v-if="episode.episode_number">{{ episode.episode_number }}</span>
              <span v-if="!episode.episode_number">{{ episode.id }}</span>
            </div>
            <div v-if="showEpisodeStore.releaseDateTimeInUserTz">
              <span class="text-xs capitalize font-semibold mr-2">
                {{ formatDate(showEpisodeStore.releaseDateTimeInUserTz) }}
              </span>
            </div>
          </div>

          <div class="upcomingDateTime">

            <div class="text-center mb-3 pr-3 pt-6">
              <button :disabled="goLiveStore.displayEpisodeGoLiveComponent" v-if="episode.status.id === 5"
                      onclick="scheduleReleaseNotice.showModal()"
                      class="bg-green-600 hover:bg-green-500 text-white rounded-lg font-semibold px-4 py-2 mb-4 mr-2 disabled:bg-gray-400">
                Schedule Release
              </button>
              <br/>
              <div v-if="episode.status.id === 6">
                <span class="text-2xl capitalize font-semibold">Scheduled Release:</span><br/>
                <span class="text-3xl capitalize font-semibold"><ConvertDateTimeToTimeAgo :dateTime="showEpisodeStore.scheduleReleaseDateTimeInUserTz" :timezone="userStore.timezone"/></span>
                <span class="text-xl capitalize font-semibold">{{ showEpisodeStore.formattedScheduledReleaseDateTime }}</span>
              </div>
            </div>

          </div>
        </div>
      </div>
    </header>

  </div>
</template>

<script setup>
import { useTimeAgo } from '@vueuse/core'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useGoLiveStore } from "@/Stores/GoLiveStore"
import { useTeamStore } from "@/Stores/TeamStore"
import { useUserStore } from '@/Stores/UserStore'
import { useShowEpisodeStore } from '@/Stores/ShowEpisodeStore'
import Button from "@/Jetstream/Button.vue"
import ShowEpisodeManageTopBanner from "@/Components/Pages/ShowEpisodes/Layout/ManageShowEpisodeTopBanner"
import EpisodeHeader from "@/Components/Pages/ShowEpisodes/Layout/EpisodeHeader"
import ConvertDateTimeToTimeAgo from '@/Components/Global/DateTime/ConvertDateTimeToTimeAgo.vue'

const appSettingStore = useAppSettingStore()
const goLiveStore = useGoLiveStore()
const teamStore = useTeamStore()
const userStore = useUserStore()
const showEpisodeStore = useShowEpisodeStore()

let props = defineProps({
  show: Object,
  team: Object,
  episode: Object,
  episodeStatus: Object,
})

</script>
