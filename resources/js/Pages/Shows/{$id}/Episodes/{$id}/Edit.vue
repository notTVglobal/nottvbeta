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

              <CreateEpisodeErrorsContainer :errors="form.errors"/>

              <form @submit.prevent="submit">
                                                <div class="flex justify-end mr-2 mb-6">
                                                    <button
                                                        @click="submit"
                                                        class="bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4"
                                                        :disabled="form.processing"
                                                    >
                                                        Save
                                                    </button>
                                                </div>


                <!-- Start grid 2-col -->
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 space-x-6 px-6">

                  <!-- Start Left Column -->
                  <div class="xl:col-span-2">

                    <EditEpisodeShowInfo />

                    <CreateEpisodeSetName :errors="form.errors"/>

                    <CreateEpisodeSetNotes :errors="form.errors"/>


                    <CreateEpisodeSetEpisodeNumber :errors="form.errors"/>


                    <CreateEpisodeSetDescription :errors="form.errors" :description="episode.description"/>


                    <CreateEpisodeSetCreativeCommons :errors="form.errors"/>


                    <CreateEpisodeSetVideoEmbed :errors="form.errors"/>
                  </div>
                  <!-- End Left Column -->
                  <!-- Start Right Column-->
                  <div>

                    <EditEpisodeVideoContainer />

                    <CreateEpisodeAttachRecording :errors="form.errors"/>

                    <CreateEpisodeUploadVideo />


                    <CreateEpisodeChangeImage :image="image" @reloadImage="reloadImageHandler" :errors="form.errors"/>


                  </div>
                  <!-- End Right Column -->
                </div>
                <!-- End grid 2-col -->

                <div class="flex justify-end mb-6">
                  <JetValidationErrors class="mr-4"/>
                  <div v-if="showEpisodeStore.isUploading" class="mr-2 font-semibold bg-black text-white px-3 py-2">Please wait until
                    uploading completes to save the form...
                  </div>
                  <button
                      @click.prevent="submit"
                      class="h-fit bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4 mr-5 disabled:bg-gray-500 disabled:cursor-not-allowed"
                      :disabled="form.processing || showEpisodeStore.isUploading"
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
import { computed, onBeforeUnmount, onMounted, onUnmounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { format } from 'date-fns'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useShowEpisodeStore } from '@/Stores/ShowEpisodeStore'
import { useTeamStore } from '@/Stores/TeamStore'
import { useShowStore } from '@/Stores/ShowStore'
import { useUserStore } from '@/Stores/UserStore'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import ShowEpisodeEditHeader from '@/Components/Pages/ShowEpisodes/Layout/EditShowEpisodeHeader'


import Message from '@/Components/Global/Modals/Messages'


import EpisodeFooter from '@/Components/Pages/ShowEpisodes/Layout/EpisodeFooter.vue'
import CreateEpisodeErrorsContainer from '@/Components/Pages/ShowEpisodes/Elements/CreateEpisodeErrorsContainer.vue'
import CreateEpisodeSetNotes from '@/Components/Pages/ShowEpisodes/Elements/CreateEpisodeSetNotes.vue'
import CreateEpisodeSetCreativeCommons
  from '@/Components/Pages/ShowEpisodes/Elements/CreateEpisodeSetCreativeCommons.vue'
import CreateEpisodeSetName from '@/Components/Pages/ShowEpisodes/Elements/CreateEpisodeSetName.vue'
import CreateEpisodeSetEpisodeNumber from '@/Components/Pages/ShowEpisodes/Elements/CreateEpisodeSetEpisodeNumber.vue'
import CreateEpisodeSetDescription from '@/Components/Pages/ShowEpisodes/Elements/CreateEpisodeSetDescription.vue'
import CreateEpisodeSetVideoEmbed from '@/Components/Pages/ShowEpisodes/Elements/CreateEpisodeSetVideoEmbed.vue'
import CreateEpisodeAttachRecording from '@/Components/Pages/ShowEpisodes/Elements/CreateEpisodeAttachRecording.vue'
import CreateEpisodeChangeImage from '@/Components/Pages/ShowEpisodes/Elements/CreateEpisodeChangeImage.vue'
import EditEpisodeShowInfo from '@/Components/Pages/ShowEpisodes/Elements/EditEpisodeShowInfo.vue'
import EditEpisodeVideoContainer from '@/Components/Pages/ShowEpisodes/Elements/EditEpisodeVideoContainer.vue'
import CreateEpisodeUploadVideo from '@/Components/Pages/ShowEpisodes/Elements/CreateEpisodeUploadVideo.vue'

// import {DatePicker} from "v-calendar";
// import 'v-calendar/style.css';

usePageSetup('shows/slug/episodes/slug')

const appSettingStore = useAppSettingStore()
const showEpisodeStore = useShowEpisodeStore()
const teamStore = useTeamStore()
const showStore = useShowStore()

let props = defineProps({
  show: Object,
  team: Object,
  episode: Object,
  image: Object,
  creative_commons: Object,
  can: Object,
})

teamStore.setActiveTeam(props.team)
teamStore.setActiveShow(props.show)
// showStore.episodePoster = props.image.name


// const description = ref(props.team.description)


// const handleContentUpdate = (html) => {
//   description.value = html
//   form.description = html
// }

// watch(() => props.team.description, (newDescription) => {
//   description.value = newDescription
//   form.description = newDescription
// })


let form = useForm({
  name: props.episode.name,
  description: props.episode.description,
  notes: props.episode.notes,
  episode_number: props.episode.episode_number,
  // status could go here...
  release_year: props.episode.release_year,
  release_dateTime: props.episode.release_dateTime,
  scheduled_release_dateTime: props.episode.scheduled_release_dateTime,
  copyright_year: props.episode.copyright_year,
  creative_commons_id: '',
  video_url: props.episode?.video?.video_url,
  youtube_url: props.episode.youtube_url,
  video_embed_code: props.episode.video_embed_code,
})


let submit = () => {
  if (showEpisodeStore.episode.video_embed_code !== props.episode.video_embed_code && showEpisodeStore.episode?.video_url) {
    addEmbedCodeConfirm()
  } else
    form.name = showEpisodeStore.episode.name
    form.description = showEpisodeStore.episode.description
    form.notes = showEpisodeStore.episode.notes
    form.episode_number = showEpisodeStore.episode.episode_number
    form.release_year = showEpisodeStore.episode.release_year
    form.release_dateTime = showEpisodeStore.episode.release_dateTime
    form.scheduled_release_dateTime = showEpisodeStore.episode.scheduled_release_dateTime
    form.copyright_year = showEpisodeStore.episode.copyrightYear
    form.creative_commons_id = showEpisodeStore.episode.creative_commons.id
    form.video_url = showEpisodeStore.episode.video?.video_url
    form.youtube_url = showEpisodeStore.episode.youtube_url
    form.video_embed_code = showEpisodeStore.episode.video_embed_code
    form.patch(route('showEpisodes.update', props.episode.slug))
}


const reloadImageHandler = () => {
  router.reload({
    only: ['image'],
  })
}



onMounted(() => {
  showEpisodeStore.initializeShowEpisode(props.episode, props.show, props.team, props.creative_commons)
})

onBeforeUnmount(() => {
  showStore.errorMessage = ''
  showEpisodeStore.reset()
})

function addEmbedCodeConfirm() {
  if (confirm('Are you sure you want to add this embed code? It will override the video url.')) {
    form.patch(route('showEpisodes.update', props.episode.slug))
  }
}

</script>
