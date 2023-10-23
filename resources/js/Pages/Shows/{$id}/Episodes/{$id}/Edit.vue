<template>

    <Head :title="`Edit Episode: ${props.episode.name}`"/>

    <div id="topDiv" class="place-self-center flex flex-col gap-y-3">
        <div class="bg-white dark:bg-gray-800 text-black light:text-black dark:text-white px-5 mb-10">

            <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>
            <div class="alert alert-error mt-4 mx-3"
                 v-if="showStore.errorMessage">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ showStore.errorMessage }}</span>
                <button class="text-xs ml-12" @click="showStore.errorMessage = ''"> Close</button>
            </div>

            <header>
                <ShowEpisodeEditHeader :show="props.show" :team="props.team" :episode="props.episode"/>
            </header>

            <div class="flex flex-col">
                <div class="-my-2 overflow-x-none">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                            <div v-if="form.errors.name" v-text="form.errors.name"
                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
                            <div v-if="form.errors.description" v-text="form.errors.description"
                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
                            <div v-if="form.errors.episode_number" v-text="form.errors.episode_number"
                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
                            <div v-if="form.errors.notes" v-text="form.errors.notes"
                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
                            <div v-if="form.errors.video_file_url" v-text="form.errors.video_file_url"
                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
<!--                            <div v-if="form.errors.youtube_url" v-text="form.errors.youtube_url"-->
<!--                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>-->
                            <div v-if="form.errors.video_embed_code" v-text="form.errors.video_embed_code"
                                 class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>


                            <form @submit.prevent="submit">
<!--                                <div class="flex justify-end mr-2 mb-6">-->
<!--                                    <button-->
<!--                                        @click="submit"-->
<!--                                        class="bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4"-->
<!--                                        :disabled="form.processing"-->
<!--                                    >-->
<!--                                        Save-->
<!--                                    </button>-->
<!--                                </div>-->



<!-- Begin grid 2-col -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 space-x-6 p-6">

<!--Left Column-->
                                <div class="xl:col-span-2">

                                    <div class="mb-6">
                                        <label class="block mb-2 uppercase font-bold text-xs"
                                               for="notes"
                                        >
                                            Episode Notes (only the team members see the notes)
                                        </label>

                                        <input v-model="form.notes"
                                               class="border border-gray-400 p-2 w-full rounded-lg text-black"
                                               type="text"
                                               name="notes"
                                               id="notes"
                                        >
                                        <div v-if="form.errors.notes" v-text="form.errors.notes"
                                             class="text-xs text-red-600 mt-1"></div>
                                    </div>



                                    <div v-if="props.episode.status.id < 7" class="mb-6">
                                        <label class="block mb-2 uppercase font-bold text-xs"
                                               for="releaseDate"
                                        >
                                            <span v-if="props.episode.scheduled_release_dateTime">
                                                Scheduled Release Date</span>
                                            <span v-else>
                                                Schedule Release</span>
                                        </label>

                                        <div v-if="!cancelScheduledReleaseDate">
                                            <div v-if="props.episode.scheduled_release_dateTime && !selectedScheduledDateTime"
                                                 class="mb-2">
                                                {{ formatDate(props.episode.scheduled_release_dateTime)}}
                                            </div>
                                            <div v-if="selectedScheduledDateTime"
                                                 class="mb-2">
                                                {{ formatDate(selectedScheduledDateTime.date) }}
                                            </div>
                                        </div>
                                        <div v-else class="mb-2">
                                            <span class="italic">Scheduled release cancelled. Please save the changes.</span>
                                        </div>

                                        <div class="flex flex-row flex-wrap space-x-2">
                                            <DateTimePickerSelect :date="props.episode.scheduled_release_dateTime" @date-time-selected="handleScheduledDateTime" />
                                            <!-- Display the selected date and time received from DateTimePicker -->
                                            <button v-if="props.episode.scheduled_release_dateTime"
                                                    class="px-3 py-2 bg-blue-500 text-sm text-white font-semibold rounded-md"
                                                    @click.prevent="cancelScheduledRelease">Cancel Release</button>
                                        </div>


                                    </div>

                                    <div v-if="props.episode.status.id === 7" class="mb-6">
                                        <label class="block mb-2 uppercase font-bold text-xs"
                                               for="releaseDate"
                                        >
                                            Release Date
                                        </label>

                                        <div v-if="props.episode.release_dateTime && !selectedReleaseDateTime"
                                             class="mb-2">
                                            {{ formatDate(props.episode.release_dateTime)}}
                                        </div>
                                        <div v-if="selectedReleaseDateTime"
                                             class="mb-2">
                                            {{ formatDate(selectedReleaseDateTime.date) }}
                                        </div>



                                        <DateTimePickerSelect v-if="userStore.isAdmin" :date="props.episode.release_dateTime" @date-time-selected="handleReleaseDateTime">
                                            <template v-slot:buttonName>
                                                Change date
                                            </template>
                                        </DateTimePickerSelect>
                                        <!-- Display the selected date and time received from DateTimePicker -->


                                    </div>

                                    <div class="mb-6">
                                        <label class="block mb-2 uppercase font-bold text-xs"
                                               for="name"
                                        >
                                            Episode Name
                                        </label>

                                        <input v-model="form.name"
                                               class="border border-gray-400 text-gray-800 p-2 w-1/2 rounded-lg"
                                               type="text"
                                               name="name"
                                               id="name"
                                               required
                                        >
                                        <div v-if="form.errors.name" v-text="form.errors.name"
                                             class="text-xs text-red-600 mt-1"></div>
                                    </div>

                                    <div class="mb-6">
                                        <label class="block mb-2 uppercase font-bold text-xs"
                                               for="episode_number"
                                        >
                                            Episode Number
                                        </label>

                                        <input v-model="form.episode_number"
                                               class="border border-gray-400 text-gray-800 p-2 w-1/2 rounded-lg"
                                               type="text"
                                               :placeholder="props.episode.id"
                                               name="episode_number"
                                               id="episode_number"
                                        >
                                        <div v-if="form.errors.episode_number" v-text="form.errors.episode_number"
                                             class="text-xs text-red-600 mt-1"></div>
                                    </div>



                                    <div class="mb-6 w-full">
                                        <label class="block mb-2 uppercase font-bold text-xs text-light"
                                               for="description"
                                        >
                                            Description
                                        </label>
                                        <TabbableTextarea v-model="form.description"
                                                          class="border border-gray-400 text-gray-800 p-2 w-full rounded-lg"
                                                          name="description"
                                                          id="description"
                                                          rows="10" cols="30"
                                                          required
                                        />
                                        <div v-if="form.errors.description" v-text="form.errors.description"
                                             class="text-xs text-red-600 mt-1"></div>
                                    </div>

<!--                                    <div class="mb-6">-->
<!--                                        <label class="block mb-2 uppercase font-bold text-xs text-white"-->
<!--                                               for="video_file_url"-->
<!--                                        >-->
<!--                                            YouTube URL-->
<!--                                        </label>-->

<!--                                        <input v-model="form.youtube_url"-->
<!--                                               class="border border-gray-400 text-gray-800 p-2 w-full rounded-lg"-->
<!--                                               type="text"-->
<!--                                               name="youtube_url"-->
<!--                                               id="youtube_url"-->
<!--                                        >-->
<!--                                        <div v-if="form.errors.youtube_url" v-text="form.errors.youtube_url"-->
<!--                                             class="text-xs text-red-600 mt-1"></div>-->
<!--                                    </div>-->

                                    <div class="mb-6">
                                        <label class="block mb-2 uppercase font-bold text-xs"
                                               for="video_file_url"
                                        >
                                            Video URL (External MP4 only)
                                        </label>

                                        <input v-model="form.video_url"
                                               class="border border-gray-400 text-gray-800 p-2 w-full rounded-lg"
                                               type="text"
                                               name="video_url"
                                               id="video_url"
                                        >
                                        <div class="text-xs mt-1">
                                            Example: <span class="underline">https://domainname.com/filename.mp4</span>
                                        </div>
                                        <div v-if="form.errors.video_url" v-text="form.errors.video_url"
                                             class="text-xs text-red-600 mt-1"></div>
                                    </div>

                                    <div class="mb-6">
                                        <label class="block mb-2 uppercase font-bold text-xs"
                                               for="video_embed_code"
                                        >
                                            Embed Code (Rumble or Bitchute only) <span class="">*</span>
                                        </label>

                                        <TabbableTextarea v-model="form.video_embed_code"
                                                          class="border border-gray-400 text-gray-800 p-2 w-full rounded-lg"
                                                          type="text"
                                                          name="video_embed_code"
                                                          id="video_embed_code"
                                                          rows="10" cols="30"
                                        />
                                        <div v-if="form.errors.video_embed_code" v-text="form.errors.video_embed_code"
                                             class="text-xs mt-1"></div>
                                    </div>

                                    <div class="mt-2 mb-6 pb-4 border-b">
                                        <div class="mb-2 block uppercase font-bold text-xs">
                                            * Notes about video embedding:
                                        </div>
                                        <ul class="list-decimal pb-2 ml-2">
                                            <li>
                                                If both URL and Embed Code are provided the system will attempt to get the Video Url from the Embed Code.
                                            </li>
                                            <li>
                                                We have <span class="font-bold">not</span> enabled the use of Facebook videos for security purposes.
                                            </li>
                                            <li>
                                                If you want to use YouTube, enter the YouTube video URL above in the YouTube URL field. This option is least preferable, due to a lower quality user experience.
                                            </li>
                                        </ul>
                                    </div>


                                </div>
<!-- End Left Column -->

<!--Right Column-->
                                <div>

                                    <div>
                                        <label class="block mb-2 uppercase font-bold text-xs"
                                               for="episodeVideo"
                                        >
                                            Upload Episode
                                        </label>
                                        <div class="max-full mx-auto mt-2 mb-6 bg-gray-200 p-6">
                                            <h2 class="text-xl font-semibold text-gray-800">Upload Video</h2>

                                            <ul class="pb-4 text-gray-800">
                                                <li>Max Video Length: <span class="text-orange-400">4 hours</span>
                                                </li>
                                                <li>File Types accepted: <span class="text-orange-400">mp4, webm, ogg</span>
                                                </li>
                                            </ul>

                                            <VideoUpload :showEpisodeId="episode.id" class="text-black"/>

                                        </div>

                                    </div>


                                    <div>
                                        <label class="block mb-2 uppercase font-bold text-xs"
                                               for="name"
                                        >
                                            Change Episode Poster
                                        </label>
                                        <div class="max-full mx-auto mt-2 mb-6 bg-gray-200 p-6">

                                            <ImageUpload :image="props.image"
                                                         :server="'/showEpisodesUploadPoster'"
                                                         :name="'Upload Episode Poster'"
                                                         :maxSize="'20MB'"
                                                         :fileTypes="'image/jpg, image/jpeg, image/png'"
                                                         @reloadImage="reloadImage"
                                            />

                                            <div class="flex space-y-3">
                                                <div class="mb-6">
                                                    <SingleImage :image="props.image" :key="props.image"/>
                                                </div>
                                            </div>



                                        </div>

                                    </div>



                                </div>


<!-- End Right Column -->
                            </div>
<!-- End grid 2-col -->

                                <div class="flex justify-end mb-6">
                                    <JetValidationErrors class="mr-4" />
                                    <button
                                        @click.prevent="submit"
                                        class="h-fit bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4 mr-5"
                                        :disabled="form.processing"
                                    >
                                        Save
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>

</template>

<script setup>
import {onBeforeMount, onMounted, onUnmounted, ref} from "vue"
import { Inertia } from "@inertiajs/inertia"
import { useForm } from "@inertiajs/inertia-vue3"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useTeamStore } from "@/Stores/TeamStore.js"
import { useShowStore } from "@/Stores/ShowStore.js"
import { useUserStore } from "@/Stores/UserStore";
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';
import TabbableTextarea from "@/Components/TabbableTextarea"
import ShowEpisodeEditHeader from "@/Components/ShowEpisodes/Edit/ShowEpisodeEditHeader"
import Message from "@/Components/Modals/Messages";
import ImageUpload from "@/Components/Uploaders/ImageUpload.vue";
import SingleImage from "@/Components/Multimedia/SingleImage.vue";
import VideoUpload from "@/Components/Uploaders/VideoUpload"
import videojs from "video.js";
import DateTimePickerSelect from "@/Components/Calendar/DateTimePickerSelect";
import { format } from 'date-fns-tz';
// import {DatePicker} from "v-calendar";
// import 'v-calendar/style.css';

let videoPlayerStore = useVideoPlayerStore()
let teamStore = useTeamStore()
let showStore = useShowStore()
let userStore = useUserStore()

let props = defineProps({
    show: Object,
    team: Object,
    episode: Object,
    image: Object,
    can: Object,
});

userStore.currentPage = 'episodes'
userStore.showFlashMessage = true;
teamStore.setActiveTeam(props.team);
teamStore.setActiveShow(props.show);
showStore.episodePoster = props.image.name;

let scheduledDateTime = ref(''); // This will hold the selected date and time in ISO format
let releaseDateTime = ref(''); // This will hold the selected date and time in ISO format

// Define a ref to store selected date and time received from DateTimePicker
let selectedReleaseDateTime = ref('');
let selectedScheduledDateTime = ref('');
let cancelScheduledReleaseDate = ref(false);

let formattedReleaseDateTime = ref(''); // This will display the formatted date and time
let formattedScheduledDateTime = ref(''); // This will display the formatted date and time

let userReleaseDateTime = ref(''); // This will display the date and time in the user's timezone
let userScheduledDateTime = ref(''); // This will display the date and time in the user's timezone

const userTimezone = ref('');

// TODO: convert this to the user's local time
releaseDateTime = props.episode.release_dateTime
scheduledDateTime = props.episode.scheduled_release_dateTime

const getUserTimezone = () => {
    // Use the Intl object to get the user's timezone
    userTimezone.value = Intl.DateTimeFormat().resolvedOptions().timeZone;
};

const convertToTimeZone = (dateTime, userTimezone) => {
    return format(dateTime, 'yyyy-MM-dd HH:mm:ssXXX', { userTimezone });
};

if (releaseDateTime) {
    userReleaseDateTime.value = convertToTimeZone(
        new Date(releaseDateTime),
        userTimezone.value);
    console.log('user release dateTime: ' + userReleaseDateTime.value)
}

if (scheduledDateTime) {
    userScheduledDateTime.value = convertToTimeZone(
        new Date(scheduledDateTime),
        userTimezone.value);
    console.log('user scheduled dateTime: ' + userScheduledDateTime.value)
}


let form = useForm({
    id: props.episode.id,
    name: props.episode.name,
    episode_number: props.episode.episode_number,
    description: props.episode.description,
    notes: props.episode.notes,
    show_id: props.episode.show_id,
    video_url: props.episode.video.video_url,
    youtube_url: props.episode.youtube_url,
    video_embed_code: props.episode.video_embed_code,
    release_dateTime: '',
    scheduled_release_dateTime: '',
});

let reloadImage = () => {
    Inertia.reload({
        only: ['image'],
    });
};

let submit = () => {
    if(form.video_embed_code !== props.episode.video_embed_code && form.video_url) {
        addEmbedCodeConfirm();
    } else
    form.put(route('showEpisodes.update', props.episode.slug));
};

// Compare the converted date to the current date in the user's timezone
const currentDate = convertToTimeZone(
    new Date(),
    userTimezone.value
);

const handleReleaseDateTime = (newDate) => {
    let changedDate = convertToTimeZone(
        newDate.date,
        userTimezone.value
    )
console.log(changedDate)
    console.log(currentDate)
    // if release dateTime is in the future, alert and return
    if (changedDate > currentDate) {
        // selectedReleaseDateTime.value = props.episode.release_dateTime
        return alert("The selected release date and time is in the future! Please select a date/time in the past.");
    } else {
        // else proceed
        selectedReleaseDateTime.value = newDate;
        releaseDateTime = newDate.date;
        // console.log(releaseDateTime)
        updateReleaseDateTime()
        console.log(formattedReleaseDateTime.value)
        form.release_dateTime = formattedReleaseDateTime
        form.scheduled_release_dateTime = null
    }
}
const handleScheduledDateTime = (newDate) => {
    selectedScheduledDateTime.value = newDate;
    scheduledDateTime = newDate.date;
    // console.log(scheduledDateTime)
    updateScheduledDateTime()
    console.log(formattedScheduledDateTime.value)

    form.scheduled_release_dateTime = formattedScheduledDateTime
    form.release_dateTime = null
}

function cancelScheduledRelease() {
    cancelScheduledReleaseDate.value = true;
    selectedScheduledDateTime.value = null;
    form.scheduled_release_dateTime = null;
}

const updateReleaseDateTime = () => {
    if (selectedReleaseDateTime.value) {
        // Convert the selected date and time to the desired time zone
        // const timeZone = 'UTC'; // Change this to your desired time zone
        formattedReleaseDateTime.value = convertToTimeZone(
            new Date(releaseDateTime),
            userTimezone.value
        );

        // Compare the converted date to the current date in the user's timezone
        // const currentDate = convertToTimeZone(
        //     new Date(),
        //     userTimezone.value
        // );
        //
        // if (formattedReleaseDateTime.value > currentDate) {
        //     props.episode.release_dateTime = userReleaseDateTime.value
        //     alert("The selected release date and time is in the future!");
        // }

    } else {
        formattedReleaseDateTime.value = '';
    }
};

const updateScheduledDateTime = () => {
    if (selectedScheduledDateTime.value) {
        // Convert the selected date and time to the desired time zone
        // const timeZone = 'UTC'; // Change this to your desired time zone
        formattedScheduledDateTime.value = convertToTimeZone(
            new Date(scheduledDateTime),
            userTimezone.value
        );
    } else {
        formattedScheduledDateTime.value = '';
    }
};

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
    getUserTimezone()
    console.log(userTimezone.value)
});

onUnmounted( () => {
    showStore.errorMessage = ''
})

function addEmbedCodeConfirm() {
    if (confirm("Are you sure you want to add this embed code? It will override the video url.")) {
        form.put(route('showEpisodes.update', props.episode.slug));
    }
}

</script>
