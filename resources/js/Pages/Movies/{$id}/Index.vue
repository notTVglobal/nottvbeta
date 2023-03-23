<template>

    <Head :title="`Movie`"/>

    <div id="topDiv" v-if="!userStore.isMobile"></div>
    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-gray-900 text-white px-5">

            <Message v-if="showMessage" @close="showMessage = false" :message="props.message"/>

            <header class="flex justify-between mb-3 border-b border-gray-800">
                <div class="container mx-auto flex flex-col lg:flex-row items-center justify-between px-4 py-6">

                    <div class="flex flex-col lg:flex-row items-center">
                        <h1 class="text-3xl font-semibold"><Link :href="route('movies')" class="hover:text-blue-800">Movies</Link></h1>
                        <ul class="flex ml-0 lg:ml-16 mt-6 lg:mt-0 space-x-8">
                            <li>
                                <Link :href="``" class="text-gray-700 cursor-not-allowed">Categories</Link>
                            </li>
                            <li>
                                <Link :href="`/movies#review`" class="hover:text-blue-800">Reviews</Link>
                            </li>
                            <li>
                                <Link :href="`/movies#coming-soon`" class="hover:text-blue-800">Coming Soon</Link>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="flex flex-end flex-wrap-reverse justify-end gap-2 mt-6 mr-4">
                <Link
                    v-if="props.can.editMovie" :href="`/movies/${props.movie.slug}/edit`"><button
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                >Edit</button>
                </Link>
                </div>

            </header>

<main class="mt-12">
            <div class="container mx-auto px-4">
                <div class="movie-details border-b border-gray-800 pb-12 flex flex-col lg:flex-row">
                    <div class="items-center">
                        <img :src="`/storage/images/EBU_Colorbars.svg.png`" alt="movie cover" class="h-96 min-w-[16rem] w-64 mb-6 lg:mb-0 m-auto lg:m-0">
                    </div>
                    <div id="topDiv" v-if="userStore.isMobile"></div>
                    <div class="lg:ml-12 lg:mr-0">
                        <h2 class="font-semibold text-4xl text-center lg:text-left">{{ movie.name }}</h2>
                        <div class="text-gray-400 text-center lg:text-left">
                            <span>Short Film, Animated</span>
                            <span v-if="movie.release_year"> &middot; {{movie.release_year}}</span>
                        </div>

                        <div class="flex flex-wrap justify-center lg:justify-start mt-4 m-auto lg:mx-0">
                            <div class="flex items-center ml-4">
                                <div class="w-16 h-16 bg-gray-800 rounded-full">
                                    <div class="font-semibold text-xs flex justify-center items-center h-full">90%</div>
                                </div>
                                <div class="ml-4 text-xs">Member <br> Rating</div>
                            </div>
                            <div class="flex items-center ml-4 lg:mr-0 lg:ml-12">
                                <div class="w-16 h-16 bg-gray-800 rounded-full">
                                    <div class="font-semibold text-xs flex justify-center items-center h-full">92%</div>
                                </div>
                                <div class="ml-4 text-xs">Audience <br> Rating</div>
                            </div>
                            <div class="flex m-auto space-x-4 lg:ml-12 pt-6 lg:mt-2 2xl:pt-0">
                                <div v-if="props.movie.www_url" class="website-url w-8 h-8 bg-gray-800 rounded-full flex justify-center items-center">
                                    <Link :href="props.movie.www_url" class="hover:text-gray-400">
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z"/>
                                        </svg>
                                    </Link>
                                </div>
                                <div v-if="props.movie.instagram_name" class="instagram-url w-8 h-8 bg-gray-800 rounded-full flex justify-center items-center">
                                    <Link :href="'https://www.instagram.com/' + props.movie.instagram_name" class="hover:text-gray-400">
                                        <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 300 300">
                                            <path d="M38.52,0.012h222.978C282.682,0.012,300,17.336,300,38.52v222.978c0,21.178-17.318,38.49-38.502,38.49
		H38.52c-21.184,0-38.52-17.313-38.52-38.49V38.52C0,17.336,17.336,0.012,38.52,0.012z M218.546,33.329
		c-7.438,0-13.505,6.091-13.505,13.525v32.314c0,7.437,6.067,13.514,13.505,13.514h33.903c7.426,0,13.506-6.077,13.506-13.514
		V46.854c0-7.434-6.08-13.525-13.506-13.525H218.546z M266.084,126.868h-26.396c2.503,8.175,3.86,16.796,3.86,25.759
		c0,49.882-41.766,90.34-93.266,90.34c-51.487,0-93.254-40.458-93.254-90.34c0-8.963,1.37-17.584,3.861-25.759H33.35v126.732
		c0,6.563,5.359,11.902,11.916,11.902h208.907c6.563,0,11.911-5.339,11.911-11.902V126.868z M150.283,90.978
		c-33.26,0-60.24,26.128-60.24,58.388c0,32.227,26.98,58.375,60.24,58.375c33.278,0,60.259-26.148,60.259-58.375
		C210.542,117.105,183.561,90.978,150.283,90.978z"/>
                                        </svg>
                                    </Link>
                                </div>
                                <div v-if="props.movie.telegram_url" class="telegram-url w-8 h-8 bg-gray-800 rounded-full flex justify-center items-center">
                                    <Link :href="props.movie.telegram_url" class="hover:text-gray-400">
                                        <svg class="w-5 h-5 fill-current pr-1" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M41.4193 7.30899C41.4193 7.30899 45.3046 5.79399 44.9808 9.47328C44.8729 10.9883 43.9016 16.2908 43.1461 22.0262L40.5559 39.0159C40.5559 39.0159 40.3401 41.5048 38.3974 41.9377C36.4547 42.3705 33.5408 40.4227 33.0011 39.9898C32.5694 39.6652 24.9068 34.7955 22.2086 32.4148C21.4531 31.7655 20.5897 30.4669 22.3165 28.9519L33.6487 18.1305C34.9438 16.8319 36.2389 13.8019 30.8426 17.4812L15.7331 27.7616C15.7331 27.7616 14.0063 28.8437 10.7686 27.8698L3.75342 25.7055C3.75342 25.7055 1.16321 24.0823 5.58815 22.459C16.3807 17.3729 29.6555 12.1786 41.4193 7.30899Z"/>
                                        </svg>
                                    </Link>
                                </div>
                                <div v-if="props.movie.twitter_handle" class="twitter-url w-8 h-8 bg-gray-800 rounded-full flex justify-center items-center">
                                    <Link :href="'https://www.twitter.com/' + props.movie.twitter_handle" class="hover:text-gray-400">
                                        <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 310 310">
                                            <path id="XMLID_827_" d="M302.973,57.388c-4.87,2.16-9.877,3.983-14.993,5.463c6.057-6.85,10.675-14.91,13.494-23.73
		c0.632-1.977-0.023-4.141-1.648-5.434c-1.623-1.294-3.878-1.449-5.665-0.39c-10.865,6.444-22.587,11.075-34.878,13.783
		c-12.381-12.098-29.197-18.983-46.581-18.983c-36.695,0-66.549,29.853-66.549,66.547c0,2.89,0.183,5.764,0.545,8.598
		C101.163,99.244,58.83,76.863,29.76,41.204c-1.036-1.271-2.632-1.956-4.266-1.825c-1.635,0.128-3.104,1.05-3.93,2.467
		c-5.896,10.117-9.013,21.688-9.013,33.461c0,16.035,5.725,31.249,15.838,43.137c-3.075-1.065-6.059-2.396-8.907-3.977
		c-1.529-0.851-3.395-0.838-4.914,0.033c-1.52,0.871-2.473,2.473-2.513,4.224c-0.007,0.295-0.007,0.59-0.007,0.889
		c0,23.935,12.882,45.484,32.577,57.229c-1.692-0.169-3.383-0.414-5.063-0.735c-1.732-0.331-3.513,0.276-4.681,1.597
		c-1.17,1.32-1.557,3.16-1.018,4.84c7.29,22.76,26.059,39.501,48.749,44.605c-18.819,11.787-40.34,17.961-62.932,17.961
		c-4.714,0-9.455-0.277-14.095-0.826c-2.305-0.274-4.509,1.087-5.294,3.279c-0.785,2.193,0.047,4.638,2.008,5.895
		c29.023,18.609,62.582,28.445,97.047,28.445c67.754,0,110.139-31.95,133.764-58.753c29.46-33.421,46.356-77.658,46.356-121.367
		c0-1.826-0.028-3.67-0.084-5.508c11.623-8.757,21.63-19.355,29.773-31.536c1.237-1.85,1.103-4.295-0.33-5.998
		C307.394,57.037,305.009,56.486,302.973,57.388z"/>

                                        </svg>
                                    </Link>
                                </div>
                            </div>

                        </div>

                        <p class="mt-12 pr-6 text-gray-300 mr-1 lg:mr-36 w-full text-center lg:text-left">
                            {{ movie.description }}
                        </p>

                        <div class="flex mt-12 m-auto lg:mx-0 justify-center lg:justify-start">
                            <button disabled class="flex bg-blue-500 text-white font-semibold px-4 py-4 hover:bg-blue-400 rounded transition ease-in-out duration-150 items-center disabled:bg-gray-600 disabled:cursor-not-allowed"
                                    @click="playTrailer">
                                <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 485 485">
                                    <path d="M413.974,71.026C368.171,25.225,307.274,0,242.5,0S116.829,25.225,71.026,71.026C25.225,116.829,0,177.726,0,242.5
		s25.225,125.671,71.026,171.474C116.829,459.775,177.726,485,242.5,485s125.671-25.225,171.474-71.026
		C459.775,368.171,485,307.274,485,242.5S459.775,116.829,413.974,71.026z M242.5,455C125.327,455,30,359.673,30,242.5
		S125.327,30,242.5,30S455,125.327,455,242.5S359.673,455,242.5,455z"/>
                                    <polygon points="181.062,336.575 343.938,242.5 181.062,148.425 	"/>
                                </svg>
                                <span class="ml-2 text-sm md:text-md">Play Trailer</span>
                            </button>

                            <button class="flex bg-blue-500 text-white font-semibold ml-4 px-4 py-4 hover:bg-blue-400 rounded transition ease-in-out duration-150 items-center disabled:bg-gray-600 disabled:cursor-not-allowed"
                                    @click="playMovie">
                                <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 485 485">
                                    <path d="M413.974,71.026C368.171,25.225,307.274,0,242.5,0S116.829,25.225,71.026,71.026C25.225,116.829,0,177.726,0,242.5
		s25.225,125.671,71.026,171.474C116.829,459.775,177.726,485,242.5,485s125.671-25.225,171.474-71.026
		C459.775,368.171,485,307.274,485,242.5S459.775,116.829,413.974,71.026z M242.5,455C125.327,455,30,359.673,30,242.5
		S125.327,30,242.5,30S455,125.327,455,242.5S359.673,455,242.5,455z"/>
                                    <polygon points="181.062,336.575 343.938,242.5 181.062,148.425 	"/>
                                </svg>
                                <span class="ml-2 text-sm md:text-md">Watch Now</span>
                            </button>



                            <button disabled class="flex bg-blue-500 text-white font-semibold ml-4 px-4 py-4 hover:bg-blue-400 rounded transition ease-in-out duration-150 items-center disabled:bg-gray-600 disabled:cursor-not-allowed">
                                <span class="text-sm md:text-md"><font-awesome-icon icon="fa-circle-down" class="mr-2"/>Save For Later</span>
                            </button>

                            <button disabled class="flex bg-blue-500 text-white font-semibold ml-4 px-4 py-4 hover:bg-blue-400 rounded transition ease-in-out duration-150 items-center disabled:bg-gray-600 disabled:cursor-not-allowed">
                                <span class="text-sm md:text-md"><font-awesome-icon icon="fa-share" class="mr-2"/>Share</span>
                            </button>

                        </div>
                    </div>
                </div>
            </div>
</main>








<!--                <div class="flex justify-center mt-12">-->
<!--                    <video class="w-full" controls>-->
<!--&lt;!&ndash;                        https://beta-staging-files.not.tv/uploads/movies/${movie.filePath}&ndash;&gt;-->
<!--                        <source-->
<!--                            v-if="!movie.fileUrl"-->
<!--                            :src="`https://nottvbeta-staging.sfo3.cdn.digitaloceanspaces.com/uploads/movies/${movie.filePath}`">-->
<!--                        <source-->
<!--                            v-if="movie.fileUrl"-->
<!--                            :src="`${movie.fileUrl}`">-->
<!--                    </video>-->

<!--                </div>-->

                <div class="flex space-x-6 mt-3">


                    <div class="mb-6 p-5">
                        <div class="font-semibold text-xs uppercase mb-3">MOVIE DESCRIPTION</div>
                        <div>{{ movie.description }}</div>
                    </div>
                </div>


                <div class="mb-6 p-5">
                    <div class="w-full bg-gray-800 text-2xl p-4 mb-8">CREDITS</div>


                    <div class="flex flex-row flex-wrap">
                        <!--                <div v-for="creator in props.creators.data"-->
                        <!--                     :key="creator.id"-->
                        <!--                     class="pb-8 bg-light dark:bg-gray-800">-->

                        <!--                    <div class="flex flex-col min-w-[8rem] px-6 py-4 font-medium break-words grow-0">-->
                        <!--                        <img :src="'/storage/' + creator.profile_photo_path" class="pb-2 rounded-full h-32 w-32 object-cover mb-2">-->
                        <!--                        <span class="light:text-gray-800 dark:text-gray-200 w-full text-center">{{ creator.name }}</span>-->
                        <!--                    </div>-->

                        <!--                            For now, we are just displaying the team members here.
                                                        This will make a good component that can be re-used across
                                                        the Show and Episode Index pages. Just pass in the creators prop.

                                                        We will add this when we have our Creators model setup
                                                        and creators attached to the credits table for this
                                                        show.                                                       -->

                        <!--                            <ShowCreatorsList />-->

                        <!--                </div>-->
                    </div>

                    <div class="w-full bg-gray-800 text-2xl p-4 mb-8">BONUS CONTENT</div>
                </div>


            <footer>

                <div class="flex justify-end mt-6 pr-2 pb-4">
                    <!-- Paginator -->
                    <!--                            <Pagination :data="`#`" class=""/>-->
                    <Link :href="`#`" class="text-blue-500 ml-2"> {{ movie.name }}
                        <span v-if="movie.release_year"> © {{movie.release_year}}</span>
                    </Link>
                </div>
            </footer>

        </div>
    </div>

</template>

<script setup>
import { onMounted, onBeforeMount, ref } from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useTeamStore } from "@/Stores/TeamStore.js"
import { useShowStore } from "@/Stores/ShowStore.js"
import { useUserStore } from "@/Stores/UserStore.js"
import Message from "@/Components/Modals/Messages"

import 'filepond/dist/filepond.min.css'
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'

let videoPlayerStore = useVideoPlayerStore()
let teamStore = useTeamStore()
let showStore = useShowStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'movies'

onBeforeMount(() => {
    userStore.scrollToTopCounter = 0;
})

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.scrollToTopCounter === 0 ) {
        document.getElementById("topDiv").scrollIntoView()
        userStore.scrollToTopCounter ++;
    }
});

let props = defineProps({
    movie: Object,
    teamName: String,
    teamSlug: String,
    creators: Object,
    message: String,
    errors: ref(''),
    video: ref(''),
    can: Object,
    // filters: Object,
});

let playTrailer = () => {
    // videoPlayer.videoSource = 'https://streams.not.tv/hls/ctd1984/index.m3u8'
    // videoPlayerStore.videoSource = 'https://mist2.not.tv/hls/kids_1/index.m3u8'
    // videoPlayer.videoSource = 'https://nottvmist.sfo3.digitaloceanspaces.com/recordings/channels_02.m3u8'
    let source = "dunepull"
    videoPlayerStore.loadNewSourceFromMist(source)
    videoPlayerStore.videoName = 'Dune'
    streamStore.currentChannel = 'On Demand'
}

let playMovie = () => {
    // videoPlayer.videoSource = 'https://streams.not.tv/hls/ctd1984/index.m3u8'
    // videoPlayerStore.videoSource = 'https://mist2.not.tv/hls/kids_1/index.m3u8'
    // videoPlayer.videoSource = 'https://nottvmist.sfo3.digitaloceanspaces.com/recordings/channels_02.m3u8'
    // let source = "dunepull"
    let source = "http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4"
    videoPlayerStore.loadNewSourceFromUrl(source)
    videoPlayerStore.videoName = 'Elephant\'s Dream'
    streamStore.currentChannel = 'On Demand'
}


let thisYear = new Date().getFullYear()
let showMessage = ref(true);

</script>
