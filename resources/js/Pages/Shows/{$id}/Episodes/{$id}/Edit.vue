<template>

  <Head :title="`Edit Episode: ${props.episode.name}`"/>

  <div id="topDiv" class="place-self-center flex flex-col gap-y-3">
    <div class="bg-white dark:bg-gray-800 text-black light:text-black dark:text-white px-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>
      <div class="alert alert-error mt-4 mx-3"
           v-if="showStore.errorMessage">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
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
                      <label class="block mb-2 uppercase font-bold text-xs text-red-700"
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

                    <div class="mb-6 w-64">
                      <label class="block mb-2 uppercase font-bold text-xs text-red-700"
                             for="creative_commons"
                      >
                        Creative Commons / Copyright
                      </label>

                      <select class="border border-gray-400 text-gray-800 py-2 pl-2 pr-8 w-fit rounded-lg block mb-2 uppercase font-bold text-xs"
                              v-model="selectedCreativeCommons" @change="handleCreativeCommonsChange">
                        <option v-for="cc in creative_commons" :key="cc.id" :value="cc.id">{{ cc.name }}</option>
                      </select>

                      <div class="">{{ selectedCreativeCommonsDescription }}</div>

                      <div v-if="form.errors.creative_commons_id" v-text="form.errors.creative_commons_idA"
                           class="text-xs text-red-600 mt-1"></div>

                    </div>

                    <div v-if="selectedCreativeCommons" class="mb-6 w-64">

                      <div v-if="selectedCreativeCommons === 8">
                        <input class="hidden border border-gray-400 text-black font-semibold p-2 w-1/2 rounded-lg"
                               type="hidden"
                               v-model="selectedCopyrightYear"
                               value="null">
                      </div>
                      <div v-else>
                        <label class="block mb-2 uppercase font-bold text-xs text-red-700"
                               for="copyrightYear"
                        >
                          Copyright Year
                        </label>
                        <input class="border border-gray-400 text-black font-semibold p-2 w-1/2 rounded-lg"
                               type="number"
                               minlength="4"
                               maxlength="4"
                               v-model="selectedCopyrightYear">
                      </div>

                      <div v-if="form.errors.copyrightYear" v-text="form.errors.copyrightYear"
                           class="text-xs text-red-600 mt-1"></div>
                    </div>


                    <div v-if="props.episode.status.id < 7" class="mb-6">
                      <label class="block mb-2 uppercase font-bold text-xs text-red-700"
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
                          {{ formatDate(props.episode.scheduled_release_dateTime) }}
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
                        <DateTimePickerSelect :date="props.episode.scheduled_release_dateTime"
                                              @date-time-selected="handleScheduledDateTime"/>
                        <!-- Display the selected date and time received from DateTimePicker -->
                        <button v-if="props.episode.scheduled_release_dateTime"
                                class="px-3 py-2 bg-blue-500 text-sm text-white font-semibold rounded-md"
                                @click.prevent="cancelScheduledRelease">Cancel Release
                        </button>
                      </div>


                    </div>

                    <div v-if="props.episode.status.id === 7" class="mb-6">
                      <label class="block mb-2 uppercase font-bold text-xs text-red-700"
                             for="releaseDate"
                      >
                        Release Date
                      </label>

                      <div v-if="props.episode.release_dateTime && !selectedReleaseDateTime"
                           class="mb-2">
                        {{ formatDate(props.episode.release_dateTime) }}
                      </div>
                      <div v-if="selectedReleaseDateTime"
                           class="mb-2">
                        {{ formatDate(selectedReleaseDateTime.date) }}
                      </div>


                      <DateTimePickerSelect v-if="can.editShow" :date="props.episode.release_dateTime"
                                            @date-time-selected="handleReleaseDateTime">
                        <template v-slot:buttonName>
                          Change date
                        </template>
                      </DateTimePickerSelect>
                      <!-- Display the selected date and time received from DateTimePicker -->


                    </div>

                    <div class="mb-6">
                      <label class="block mb-2 uppercase font-bold text-xs text-red-700"
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
                      <label class="block mb-2 uppercase font-bold text-xs text-red-700"
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
                      <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
                             for="description"
                      >
                        Description
                      </label>
                      <tip-tap-description-editor @updateContent="handleContentUpdate" :description="episode?.description"/>
<!--                      <TabbableTextarea v-model="form.description"-->
<!--                                        class="border border-gray-400 text-gray-800 p-2 w-full rounded-lg"-->
<!--                                        name="description"-->
<!--                                        id="description"-->
<!--                                        rows="10" cols="30"-->
<!--                                        required-->
<!--                      />-->
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
                      <label class="block mb-2 uppercase font-bold text-xs text-red-700"
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
                      <label class="block mb-2 uppercase font-bold text-xs text-red-700"
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
                      <div class="mb-2 block uppercase font-bold text-xs text-red-700">
                        * Notes about video embedding:
                      </div>
                      <ul class="list-decimal pb-2 ml-2">
                        <li>
                          If both URL and Embed Code are provided the system will attempt to get the Video Url from the
                          Embed Code.
                        </li>
                        <li>
                          We have <span class="font-bold">not</span> enabled the use of Facebook videos for security
                          purposes.
                        </li>
                        <li>
                          If you want to use YouTube, enter the YouTube video URL above in the YouTube URL field. This
                          option is least preferable, due to a lower quality user experience.
                        </li>
                      </ul>
                    </div>


                  </div>
                  <!-- End Left Column -->

                  <!--Right Column-->
                  <div>
                    <div class="mb-4">
                      <label class="block mb-2 uppercase font-bold text-xs text-red-700"
                             for="episodeVideo"
                      >
                        Attach Recording
                      </label>

                      <select class="rounded-lg">
                        <option>No recordings available</option>
                        <option>Select recording...</option>
                      </select>


                    </div>


                    <div>
                      <label class="block mb-2 uppercase font-bold text-xs text-red-700"
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

                        <VideoUpload :showEpisodeId="episode.id" class="text-black" @upload-start="handleUploadStart" @upload-finished="handleUploadEnd" />

                      </div>

                    </div>


                    <div>
                      <label class="block mb-2 uppercase font-bold text-xs text-red-700"
                             for="name"
                      >
                        Change Episode Poster
                      </label>
                      <div class="max-full mx-auto mt-2 mb-6 bg-gray-200 p-6">

                        <ImageUpload :image="props.image"
                                     :modelType="'showEpisode'"
                                     :modelId="episode.id"
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
                  <JetValidationErrors class="mr-4"/>
                  <div v-if="isUploading" class="mr-2 font-semibold bg-black text-white px-3 py-2">Please wait until uploading completes to save the form... </div>
                  <button
                      @click.prevent="submit"
                      class="h-fit bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4 mr-5 disabled:bg-gray-500 disabled:cursor-not-allowed"
                      :disabled="form.processing || isUploading"
                  >
                    Save
                  </button>
                </div>

              </form>

            </div>
          </div>
        </div>
      </div>

      <EpisodeFooter :can="can" :team="team" :episode="episode" :show="show"/>

    </div>
  </div>

</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { computed, onMounted, onUnmounted, ref, watch } from 'vue'
import { useForm } from "@inertiajs/vue3"
import { format } from 'date-fns'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useTeamStore } from "@/Stores/TeamStore"
import { useShowStore } from "@/Stores/ShowStore"
import { useUserStore } from "@/Stores/UserStore"
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import ShowEpisodeEditHeader from "@/Components/Pages/ShowEpisodes/Layout/EditShowEpisodeHeader"

import DateTimePickerSelect from "@/Components/Global/Calendar/DateTimePickerSelect"
import TabbableTextarea from "@/Components/Global/TextEditor/TabbableTextarea"

import Message from "@/Components/Global/Modals/Messages"
import ImageUpload from "@/Components/Global/Uploaders/ImageUpload"
import SingleImage from "@/Components/Global/Multimedia/SingleImage"
import VideoUpload from "@/Components/Global/Uploaders/VideoUpload"
import EpisodeFooter from '@/Components/Pages/ShowEpisodes/Layout/EpisodeFooter.vue'
import TipTapDescriptionEditor from '@/Components/Global/TextEditor/TipTapDescriptionEditor.vue'

// import {DatePicker} from "v-calendar";
// import 'v-calendar/style.css';

usePageSetup('shows/slug/episodes/slug')

const appSettingStore = useAppSettingStore()
const teamStore = useTeamStore()
const showStore = useShowStore()
const userStore = useUserStore()

let props = defineProps({
  show: Object,
  team: Object,
  episode: Object,
  image: Object,
  creative_commons: Object,
  can: Object,
});

teamStore.setActiveTeam(props.team);
teamStore.setActiveShow(props.show);
showStore.episodePoster = props.image.name;

const isUploading = ref(false);
// const selectedCreativeCommons = ref(props.episode?.creative_commons?.id);
// Initialize selectedCreativeCommons with a default value of 7 if the id is null
const selectedCreativeCommons = ref(props.episode?.creative_commons?.id || 7);
let selectedCopyrightYear = ref(props.episode?.copyrightYear);
const currentYear = new Date().getFullYear();

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

function handleUploadStart() {
  isUploading.value = true;
}

const videoId = ref('');

function handleUploadEnd(id) {
  videoId.value = id;
  console.log(`Received videoId: ${videoId.value}`);
  console.log('It\'s a DUCK!')
  console.log(`Received videoId: ${videoId.value}`);
  isUploading.value = false;
}



const getUserTimezone = () => {
  // Use the Intl object to get the user's timezone
  userTimezone.value = Intl.DateTimeFormat().resolvedOptions().timeZone;
};

const convertToTimeZone = (dateTime, userTimezone) => {
  return format(dateTime, 'yyyy-MM-dd HH:mm:ssXXX', {userTimezone});
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

const handleCreativeCommonsChange = () => {
  if (selectedCreativeCommons.value === 8) {
    selectedCopyrightYear.value = null;
  } else if (selectedCopyrightYear.value === null) {
    // Pre-populate with current year only if copyrightYear is null
    selectedCopyrightYear.value = currentYear;
  }
};

const selectedCreativeCommonsDescription = computed(() => {
  const selectedCC = props.creative_commons.find((cc) => cc.id === selectedCreativeCommons.value);
  return selectedCC ? selectedCC.description : '';
});

const description = ref(props.team.description)


const handleContentUpdate = (html) => {
  description.value = html
  form.description = html
}

watch(() => props.team.description, (newDescription) => {
  description.value = newDescription
  form.description = newDescription
})


let form = useForm({
  id: props.episode.id,
  name: props.episode.name,
  episode_number: props.episode.episode_number,
  description: description.value,
  notes: props.episode.notes,
  copyrightYear: selectedCopyrightYear,
  creative_commons_id: selectedCreativeCommons.value,
  show_id: props.episode.show_id,
  video_url: props.episode.video.video_url,
  youtube_url: props.episode.youtube_url,
  video_embed_code: props.episode.video_embed_code,
  release_dateTime: props.episode.release_dateTime,
  scheduled_release_dateTime: props.episode.scheduled_release_dateTime,
});

let reloadImage = () => {
  router.reload({
    only: ['image'],
  });
};

let submit = () => {
  if (form.video_embed_code !== props.episode.video_embed_code && form.video_url) {
    addEmbedCodeConfirm();
  } else
    form.copyrightYear = selectedCopyrightYear;
    form.creative_commons_id = selectedCreativeCommons.value;
    form.patch(route('showEpisodes.update', props.episode.slug));
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
  getUserTimezone()
  console.log(userTimezone.value)
  watch(() => props.episode?.creative_commons?.id, (newVal) => {
    // selectedCreativeCommons.value = newVal;
    // Set selectedCreativeCommons to newVal if it's not null, otherwise default to 7
    selectedCreativeCommons.value = newVal !== null ? newVal : 7;
    handleCreativeCommonsChange();
  });
  // Call handleCreativeCommonsChange immediately to handle the initial value
  handleCreativeCommonsChange();
});

onUnmounted(() => {
  showStore.errorMessage = ''
})

function addEmbedCodeConfirm() {
  if (confirm("Are you sure you want to add this embed code? It will override the video url.")) {
    form.patch(route('showEpisodes.update', props.episode.slug));
  }
}

</script>
