<template>
    <div>
        <div v-if="teamStore.goLiveDisplay" class="mx-4 mt-4 mb-2 px-6 py-1 ">
            <div class="text-sm font-semibold bg-red-600 text-white text-center w-full border-2 border-red-600 rounded uppercase px-6 py-1 ">Go Live Instructions</div>
            <div class="shadow bg-red-100 overflow-hidden border-2 border-red-600 rounded p-6">
                <div>RTMP full url: <span class="font-bold">rtmp://streams.not.tv/live/{YOUR STREAM KEY}</span></div>
                <div>RTMP url: <span class="font-bold">rtmp://streams.not.tv/live/</span></div>
                <div>RTMP stream key: <span class="font-bold">{YOUR STREAM KEY}</span></div>
                <div>
                    <span class="italic">Your stream key is using showName+UUID.</span>
                </div>
                <div class="flex flex-row flex-wrap space-x-2 space-y-2 w-2/3 justify-between pt-10 mx-auto">
                    <div></div>
                    <div class="flex flex-col justify-center text-center space-y-2">
                        <div v-if="props.episode.show_episode_status_id === 6">
                            <div>Countdown to scheduled live:</div>
                            <div>{{timeAgo}}</div>
                        </div>
                        <button onclick="startCountdownNotice.showModal()" class="bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-lg px-4 py-2">Start Countdown</button>
                        <div class="text-xs font-bold">or</div>
                        <button onclick="startLiveStreamNotice.showModal()" class="bg-red-600 hover:bg-red-500 text-white font-semibold rounded-lg px-4 py-2">Go Live Now</button>
                    </div>
                    <div class="flex flex-col justify-center">
                        <div>VIDEO STREAM</div>
                        <div class="bg-black px-10 py-10">
                            <video-player-aux />
                        </div>

                    </div>

                </div>

                <div class="mx-4 mt-4 mb-2 px-6 py-1 ">
                    <div class="text-sm font-semibold bg-blue-600 text-white text-center w-full border-2 border-blue-600 rounded uppercase px-6 py-1 ">Push Destinations</div>
                    <div class="shadow bg-blue-100 overflow-hidden border-2 border-blue-600 rounded p-6 space-y-3">
                        <div></div>
                        <div>Set up <span class="font-bold">push destinations:</span></div>
                        <div>Here you can set additional streaming destinations such as Facebook, YouTube, Rumble, Twitch, etc. and notTV will automatically start pushing to those destinations when you go live.</div>
                        <div class="w-full flex justify-center pt-4">
                            <button onclick="setPushDestinationsNotice.showModal()" class="bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-lg px-4 py-2">Set Push Destinations</button>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <div v-if="teamStore.goLiveDisplay" class="mx-4 mt-4 mb-8 px-6 py-1 ">
            <div class="text-sm font-semibold bg-orange-600 text-white text-center w-full border-2 border-orange-600 rounded uppercase px-6 py-1 ">Commercial Breaks</div>
            <div class="shadow bg-orange-100 overflow-hidden border-2 border-orange-600 rounded p-6 space-y-3">
                <div></div>
                <div>Click the <span class="font-bold">Trigger Commercial Break</span> button below to go to commercial.</div>
                <div>You will see a countdown timer until the show resumes. This will be 1-2 minutes.</div>
                <div>As a notTV creator, your voice matters. How can this feature be improved to better serve you and your sponsors?
                    Please email and let us know: <a href="mailto:hello@not.tv" target="_blank" class="text-blue-500 hover:text-blue-600">hello@not.tv</a>
                </div>
                <div class="w-full flex justify-center pt-4">
                    <button onclick="setCommercialBreakNotice.showModal()" class="bg-orange-600 hover:bg-orange-500 text-white font-semibold rounded-lg px-4 py-2">Trigger Commercial Break</button>
                </div>
            </div>

        </div>

    </div>


</template>

<script setup>
import { useTeamStore } from "@/Stores/TeamStore.js"
import VideoPlayerAux from "@/Components/VideoPlayer/VideoPlayerAux.vue";
import { useTimeAgo } from '@vueuse/core'


const teamStore = useTeamStore();

let props = defineProps({
    episode: Object,
    scheduledDateTime: String,
})

let playerName = 'aux-player';
let videoEmbedCode = props.episode.video_file_embed_code;

// const timeAgo = useTimeAgo(new Date(2023, 10, 5))
const timeAgo = useTimeAgo(props.scheduledDateTime)
</script>
