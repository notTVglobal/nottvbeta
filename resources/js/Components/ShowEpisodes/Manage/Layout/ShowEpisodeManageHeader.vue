<template>
    <div>
        <ShowEpisodeManageTopBanner :episode="episode" :episodeStatus="episodeStatus"/>

        <header class="bg-gradient-to-r from-green-100 via-white to-transparent p-4 text-black font-bold rounded-lg">
            <div class="flex justify-between my-3">
                <div class="gap-2">
                    <div class="font-bold mb-4 text-black">MANAGE EPISODE</div>
                </div>
                <div class="flex flex-wrap-reverse justify-end gap-2">
                    <div class="" v-if="teamStore.can.goLive && !episode.video_file_url">
                        <button
                            v-if="!teamStore.goLiveDisplay"
                            @click="teamStore.toggleGoLiveDisplay()"
                            :disabled="episode.show_episode_status_id > 6"
                            class="px-4 py-2 text-white bg-red-600 hover:bg-red-500 rounded-lg disabled:bg-gray-400"
                        >Go Live
                        </button>
                        <button
                            v-if="teamStore.goLiveDisplay"
                            @click="teamStore.toggleGoLiveDisplay()"
                            class="px-4 py-2 text-white bg-red-600 hover:bg-red-500 rounded-lg disabled:bg-gray-400"
                        >Cancel
                        </button>
                    </div>
                    <div>
                        <button
                            v-if="teamStore.can.editEpisode"
                            @click="userStore.btnRedirect(`/shows/${show.slug}/episode/${episode.slug}/edit`)"
                            :disabled="teamStore.goLiveDisplay"
                            class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg disabled:bg-gray-400"
                        >Edit</button>
                    </div>
                    <div>
                        <button
                            :disabled="teamStore.goLiveDisplay"
                            @click="userStore.btnRedirect(`/shows/${show.slug}/manage`)"
                            class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg disabled:bg-gray-400"
                        >Manage Show
                        </button>
                    </div>
                    <div>
                        <button
                            :disabled="teamStore.goLiveDisplay"
                            @click="userStore.btnRedirect('/dashboard')"
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
                        @click="userStore.btnRedirect(`/shows/${show.slug}/episode/${episode.slug}/upload`)"
                        :disabled="teamStore.goLiveDisplay"
                        class="px-4 py-2 text-white font-semibold bg-orange-600 hover:bg-orange-500 rounded-lg disabled:bg-gray-400"
                    >Upload Video</button>
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
                <div class="flex flex-col">
                    <div class="text-center mb-3 pr-3">
                        <button :disabled="teamStore.goLiveDisplay" v-if="episode.show_episode_status_id === 5" onclick="scheduleReleaseNotice.showModal()" class="bg-green-600 hover:bg-green-500 text-white rounded-lg font-semibold px-4 py-2 mb-4 mr-2 disabled:bg-gray-400">Schedule Release</button><br />
                        <span v-if="episode.show_episode_status_id === 6" class="text-xs capitalize font-semibold">Scheduled Release</span><br />
                        <span v-if="episode.show_episode_status_id === 6" class="capitalize font-semibold">{{timeAgo}}</span>
                    </div>
                    <div><span class="text-xs capitalize font-semibold">Show: </span>
                        <button :disabled="teamStore.goLiveDisplay" @click="userStore.btnRedirect(`/shows/${show.slug}/manage`)" class="text-blue-500 ml-2 uppercase disabled:text-black">
                            {{ show.name }}
                        </button>
                    </div>
                    <div><span class="text-xs capitalize font-semibold mr-2">Show Runner: </span>
                        {{ show.showRunner }}
                    </div>
                    <div><span class="text-xs capitalize font-semibold mr-2">Episode Number: </span>
                        <span v-if="episode.episode_number">{{ episode.episode_number }}</span>
                        <span v-if="!episode.episode_number">{{ episode.id }}</span>
                    </div>
                    <div v-if="releaseDateTime"><span class="text-xs capitalize font-semibold mr-2">
                                {{ formatDate(releaseDateTime) }}
                            </span></div>

                </div>


            </div>


        </header>

    </div>

</template>

<script setup>
import { useTeamStore } from "@/Stores/TeamStore.js"
import { useUserStore } from "@/Stores/UserStore";
import EpisodeHeader from "@/Components/ShowEpisodes/EpisodeHeader";
import ShowEpisodeManageTopBanner from "@/Components/ShowEpisodes/Manage/Layout/ShowEpisodeManageTopBanner";
import { useTimeAgo } from '@vueuse/core'
import Button from "@/Jetstream/Button.vue";

const teamStore = useTeamStore();
const userStore = useUserStore()

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
