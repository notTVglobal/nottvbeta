<template>
  <div>
    <ShowEpisodeManageTopBanner :episode="episode" :episodeStatus="episodeStatus"/>

    <header class="bg-gradient-to-r from-green-100 via-white to-transparent p-4 text-black rounded-lg">
      <div class="flex justify-between my-3">
        <div class="gap-2">
          <div class="font-bold mb-4 text-black">MANAGE EPISODE</div>
        </div>
        <div class="flex flex-wrap-reverse justify-end gap-2">
          <div class="" v-if="teamStore.can.goLive && !episode.video_file_url">
            <button
                v-if="!goLiveStore.displayEpisodeGoLiveComponent"
                @click="goLiveStore.toggleDisplayEpisodeGoLiveComponent(episode)"
                :disabled="episode.show_episode_status_id > 6"
                class="hidden px-4 py-2 text-white bg-red-600 hover:bg-red-500 rounded-lg disabled:bg-gray-400"
            >Go Live
            </button>
            <button
                v-if="goLiveStore.displayEpisodeGoLiveComponent"
                @click="goLiveStore.toggleDisplayEpisodeGoLiveComponent()"
                class="px-4 py-2 text-white bg-red-600 hover:bg-red-500 rounded-lg disabled:bg-gray-400"
            >Cancel
            </button>
          </div>
          <div>
            <button
                v-if="teamStore.can.editEpisode"
                @click="appSettingStore.btnRedirect(`/shows/${show.slug}/episode/${episode.slug}/edit`)"
                :disabled="goLiveStore.displayEpisodeGoLiveComponent"
                class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg disabled:bg-gray-400"
            >Edit
            </button>
          </div>
          <div>
            <button
                :disabled="goLiveStore.displayEpisodeGoLiveComponent"
                @click="appSettingStore.btnRedirect(`/shows/${show.slug}/manage`)"
                class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg disabled:bg-gray-400"
            >Manage Show
            </button>
          </div>
          <div>
            <button
                :disabled="goLiveStore.displayEpisodeGoLiveComponent"
                @click="appSettingStore.btnRedirect('/dashboard')"
                class="bg-black hover:bg-gray-800 text-white font-semibold px-4 py-2 rounded-lg disabled:bg-gray-400"
            >Dashboard
            </button>

          </div>
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
            <div v-if="releaseDateTime">
              <span class="text-xs capitalize font-semibold mr-2">
                {{ formatDate(releaseDateTime) }}
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
                <span class="text-3xl capitalize font-semibold">{{ timeAgo }}</span>
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
import Button from "@/Jetstream/Button.vue"
import ShowEpisodeManageTopBanner from "@/Components/Pages/ShowEpisodes/Layout/ManageShowEpisodeTopBanner"
import EpisodeHeader from "@/Components/Pages/ShowEpisodes/Layout/EpisodeHeader"

const appSettingStore = useAppSettingStore()
const goLiveStore = useGoLiveStore()
const teamStore = useTeamStore()

let props = defineProps({
  show: Object,
  team: Object,
  episode: Object,
  episodeStatus: Object,
  scheduledDateTime: String,
  releaseDateTime: String,
})

const timeAgo = useTimeAgo(props.scheduledDateTime)


</script>
