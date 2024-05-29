<template>
  <Head title="Create Episode"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

      <!--            <Message v-if="appSettingStore.showFlashMessage && $page.props.flash" :flash="$page.props.flash"/>-->

      <div class="flex justify-between mt-3 mb-6">
        <div class="text-3xl">Create Episode</div>
        <div>
          <CancelButton/>
        </div>
      </div>


      <form @submit.prevent="submit" class="mt-8">


        <div class="flex flex-row flex-wrap-reverse w-full px-6 justify-center gap-x-8 gap-y-2">
          <div class="flex flex-col max-w-md">

            <div class="mb-6">
              <label class="block mb-1 uppercase font-bold text-xs dark:text-gray-200"
                     for="showName"
              >
                Show Name
              </label>
              <div class="font-bold text-3xl">{{ props.show.name }}</div>

              <div v-if="form.errors.show_id" v-text="form.errors.show_id" class="text-xs text-red-600 mt-1"></div>
            </div>

            <div class="mb-6">
              <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
                     for="showCategory"
              >
                Category
              </label>
              <div class="font-bold">{{ props?.show?.category?.name }}</div>
              <div class="font-semibold">{{ props?.show?.subCategory?.name }}</div>
            </div>

            <div class="mb-6">
              <label class="block mb-2 uppercase font-bold  dark:text-gray-200"
                     for="name"
              >
                Episode Name <span :class="form.errors.name ? 'text-red-500' : 'text-indigo-500'">* REQUIRED</span>
              </label>

              <input v-model="form.name"
                     class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500"
                     type="text"
                     name="name"
                     id="name"
                     required
                     placeholder="Episode Name"
              >
              <div v-if="form.errors.name" v-text="form.errors.name" class="text-xs text-red-600 mt-1"></div>
            </div>

            <CreateEpisodeSetDescription :errors="form.errors"/>


            <div class="mb-6">
              <label class="block mb-2 uppercase font-bold dark:text-gray-200"
                     for="episode_number"
              >
                Episode Number
              </label>

              <input v-model="form.episode_number"
                     class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-1/2 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                     type="text"
                     name="episode_number"
                     id="episode_number"
                     placeholder="E0000"
              >
              <div v-if="form.errors.episode_number" v-text="form.errors.episode_number"
                   class="text-xs text-red-600 mt-1"></div>
            </div>

            <!--                <div class="mb-6">-->
            <!--                    <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"-->
            <!--                           for="youtube_url"-->
            <!--                    >-->
            <!--                        YouTube URL-->
            <!--                    </label>-->
            <!--                    <input v-model="form.youtube_url"-->
            <!--                           class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500"-->
            <!--                           type="text"-->
            <!--                           name="youtube_url"-->
            <!--                           id="youtube_url"-->
            <!--                    >-->
            <!--                    <div v-if="form.errors.youtube_url" v-text="form.errors.youtube_url" class="text-xs text-red-600 mt-1"></div>-->
            <!--                </div>-->

            <div class="mb-6">
              <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
                     for="video_url"
              >
                Video URL (External MP4 only)
              </label>
              <input v-model="form.video_url"
                     class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500"
                     type="text"
                     name="video_url"
                     id="video_url"
              >
              <div class="text-xs mt-1">
                Example: <span class="underline">https://domainname.com/filename.mp4</span>
              </div>
              <div v-if="form.errors.video_url" v-text="form.errors.video_url" class="text-xs text-red-600 mt-1"></div>
            </div>

            <div class="mb-6">
              <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
                     for="video_embed_code"
              >
                * Embed Code (Rumble or Bitchute only)
              </label>
              <textarea v-model="form.video_embed_code"
                        class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 block"
                        type="text"
                        name="video_embed_code"
                        id="video_embed_code"
              ></textarea>
              <div v-if="form.errors.video_embed_code" v-text="form.errors.video_embed_code"
                   class="text-xs text-red-600 mt-1"></div>
            </div>

            <div class="mt-2 mb-6 pb-4 border-b">
              <div class="mb-2 block uppercase font-bold text-xs">
                * Video Embedding:
              </div>
              <ul class="list-decimal pb-2 ml-2 space-y-2">
                <li>
                  If both URL and Embed Code are provided the system will attempt to get the Video Url from the Embed Code.
                </li>
                <li>
                  We have <span class="font-bold">not</span> enabled the use of Facebook videos for security purposes.
                </li>
                <li hidden>
                  If you want to use YouTube, enter the YouTube video URL above in the YouTube URL field. This option is
                  least preferrable, due to a lower quality user experience.
                </li>
              </ul>
            </div>

            <div class="mb-6">
              <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
                     for="notes"
              >
                Notes <br>(Only your team members see these notes, they are not public)
              </label>
              <textarea v-model="form.notes"
                        class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 block"
                        type="text"
                        name="notes"
                        id="notes"
              ></textarea>
              <div v-if="form.errors.notes" v-text="form.errors.notes" class="text-xs text-red-600 mt-1"></div>
            </div>

            <div class="flex justify-between mb-6">
              <JetValidationErrors class="mr-4"/>
              <button
                  type="submit"
                  class="h-fit bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4"
                  :disabled="form.processing"
              >
                Submit
              </button>
            </div>


          </div>
          <div class="flex flex-col max-w-md text-justify mt-24 mb-8">
            <div class="mb-6">
              <label class="block mb-2 uppercase font-bold dark:text-gray-200"
                     for="creative_commons"
              >
                Creative Commons / Copyright License <br /><span :class="form.errors.creative_commons_id ? 'text-red-500' : 'text-indigo-500'">* REQUIRED</span>
              </label>

              <select class="border border-gray-400 text-gray-800 mt-6 py-2 pl-2 pr-8 w-fit rounded-lg block mb-2 uppercase font-bold text-xs"
                      v-model="selectedCreativeCommons" @change="handleCreativeCommonsChange">
                <option disabled :value="null">Choose a license...</option>
                <option v-for="cc in creative_commons" :key="cc.id" :value="cc.id">{{ cc.name }}</option>
              </select>

              <div class="">{{ selectedCreativeCommonsDescription }}</div>

              <div v-if="form.errors.creative_commons_id" v-text="form.errors.creative_commons_id"
                   class="text-xs text-red-600 mt-1"></div>

            </div>


            <div v-if="selectedCreativeCommons" class="w-64">

              <div v-if="selectedCreativeCommons === 8">
                <input class="hidden border border-gray-400 text-black font-semibold p-2 w-1/2 rounded-lg mb-6"
                       type="hidden"
                       v-model="selectedCopyrightYear"
                       value="null">
              </div>
              <div v-else>
                <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
                       for="copyrightYear"
                >
                  Copyright Year
                </label>
                <input class="border border-gray-400 text-black font-semibold p-2 w-1/2 rounded-lg mb-6"
                       type="number"
                       minlength="4"
                       maxlength="4"
                       v-model="selectedCopyrightYear">
              </div>

              <div v-if="form.errors.copyrightYear" v-text="form.errors.copyrightYear"
                   class="text-xs text-red-600 mt-1 mb-6"></div>
            </div>

            <LicensingExplained class="my-2"/>

            <CreativeCommonsAboutTheLicenses />

          </div>
        </div>


      </form>

      <div class="flex justify-end mt-6">
        <Link :href="`/teams/${props.team.slug}`" class="text-blue-500 ml-2"> {{ props.team.name }} © 2022</Link>
      </div>

    </div>
  </div>


</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { useForm } from "@inertiajs/vue3"
import { usePageSetup } from '@/Utilities/PageSetup'
import { useShowEpisodeStore } from '@/Stores/ShowEpisodeStore'
import { useNotificationStore } from '@/Stores/NotificationStore'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import CancelButton from "@/Components/Global/Buttons/CancelButton"
import LicensingExplained from '@/Components/Global/CreativeCommonsLicensing/LicensingExplained.vue'
import CreateEpisodeSetDescription from '@/Components/Pages/ShowEpisodes/Elements/CreateEpisodeSetDescription.vue'
import CreativeCommonsAboutTheLicenses
  from '@/Components/Pages/ShowEpisodes/Elements/CreativeCommonsAboutTheLicenses.vue'

usePageSetup('shows/slug/episodes/create')

const showEpisodeStore = useShowEpisodeStore()
const notificationStore = useNotificationStore()

let props = defineProps({
  user: Object,
  show: Object,
  team: Object,
  creative_commons: Object,
})

// appSettingStore.currentPage = 'episodes'
// appSettingStore.showFlashMessage = true;

// if (props.show) {
//     teamStore.setActiveShow(props.show);
// }
//
// if (props.team) {
//     teamStore.setActiveTeam(props.team);
// }

let selectedCreativeCommons = ref(null);
let selectedCopyrightYear = ref(null);
const currentYear = new Date().getFullYear();

let form = useForm({
  name: '',
  description: '',
  user_id: props.user.id,
  show_id: props.show.id,
  show_slug: props.show.slug,
  episode_number: '',
  video_url: '',
  youtube_url: '',
  video_embed_code: '',
  notes: '',
  creative_commons_id: '',
  copyrightYear: '',
});

function reset() {
  form.reset();
}

function addEmbedCodeConfirm() {
  if (confirm("Are you sure you want to add this embed code? It will override the video url.")) {
    form.post(route('showEpisodes.store', props.show.slug));
  }
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


onMounted(() => {
  notificationStore.reset()
  // Watch for confirmation result
  watch(() => notificationStore.confirmation, (newValue) => {
    if (newValue !== null) {
      if (newValue) {
        // User confirmed, load cached content
        form.creative_commons_id = selectedCreativeCommons.value
        form.copyrightYear = selectedCopyrightYear.value
        form.description = showEpisodeStore.episode.description
        form.post(route('showEpisodes.store', props.show.slug));
      } else {
        // User cancelled, load initial content
      }
      notificationStore.clearConfirmNotification()
    }
  })
})


let submit = () => {
  if (form.video_embed_code && form.video_url) {
    notificationStore.setConfirmNotification('Confirm', 'Are you sure you want to add this embed code? It will override the video url.')
    return
  } else
    form.creative_commons_id = selectedCreativeCommons.value
    form.copyrightYear = selectedCopyrightYear.value
    form.description = showEpisodeStore.episode.description
    form.post(route('showEpisodes.store', props.show.slug));
};

// let submit = () => {
//     form.patch(route('shows.showEpisodes.store'));
// };

// let showMessage = ref(true);

// function back() {
//     let urlPrev = usePage().props.urlPrev
//     if (urlPrev !== 'empty') {
//         router.visit(urlPrev)
//     }
//     if (urlPrev === 'empty') {
//         router.visit('/shows/'+props.show.slug+'/manage')
//     }
// }

</script>

<style scoped>
.license-container {
  margin: 20px;
}
h2 {
  color: #333;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  margin-bottom: 10px;
  line-height: 1.6;
}
</style>
