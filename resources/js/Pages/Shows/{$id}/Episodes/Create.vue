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

      <div class="mx-4">
        <div class="alert alert-info mt-4">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
               class="stroke-current shrink-0 w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <span><span class="font-semibold">NOTE: </span>
                    We are working on an episode poster and video uploader for this page. For the time being, please
                    go to the episode EDIT page after you create the episode to add a video and a poster.</span>
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
              <div class="font-bold text-orange-600">{{ props.show.category.name }}</div>
              <div class="font-bold text-yellow-700">{{ props.show.subCategory.name }}</div>
            </div>

            <div class="mb-6">
              <label class="block mb-2 uppercase font-bold dark:text-gray-200"
                     for="creative_commons"
              >
                Creative Commons / Copyright License <br /><span class="text-md text-red-500">* REQUIRED</span>
              </label>
              <div class="font-semibold">** Please choose carefully ** <br />
                You will require assistance to change this once it's set.</div>
              <div>Read about the different licenses to the right right <span class="text-xl">👉</span> <br />or click the button below for a visual explanation.</div>

              <select class="border border-gray-400 text-gray-800 mt-2 py-2 pl-2 pr-8 w-fit rounded-lg block mb-2 uppercase font-bold text-xs"
                      v-model="selectedCreativeCommons" @change="handleCreativeCommonsChange">
                <option disabled :value="null">Choose a license...</option>
                <option v-for="cc in creative_commons" :key="cc.id" :value="cc.id">{{ cc.name }}</option>
              </select>

              <div class="">{{ selectedCreativeCommonsDescription }}</div>

              <div v-if="form.errors.creative_commons_id" v-text="form.errors.creative_commons_id"
                   class="text-xs text-red-600 mt-1"></div>

              <LicensingExplained class="my-2"/>
            </div>

            <div class="mb-6">
              <label class="block mb-2 uppercase font-bold  dark:text-gray-200"
                     for="name"
              >
                Episode Name <span class="text-red-500">* REQUIRED</span>
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

            <div class="mb-6">
              <label class="block mb-2 uppercase font-bold dark:text-gray-200"
                     for="description"
              >
                Description <span class="text-red-500">* REQUIRED</span>
              </label>
              <textarea v-model="form.description"
                        class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 block"
                        type="text"
                        name="description"
                        id="description"
                        required
                        placeholder="Description"
              ></textarea>
              <div v-if="form.errors.description" v-text="form.errors.description" class="text-xs text-red-600 mt-1"></div>
            </div>


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
            <div>
              <div class="license-info">
                <h2 class="font-semibold mb-6">About The License Options:</h2>
                <h3 class="font-semibold">Creative Commons Licenses</h3>
                <ul>
                  <li v-for="license in licenses.filter(l => l.type === 'Creative Commons')" :key="license.id">
                    <strong>{{ license.name }}:</strong> {{ license.description }}
                  </li>
                </ul>

                <h3 class="font-semibold">Legacy Copyright</h3>
                <ul>
                  <li v-for="license in licenses.filter(l => l.type === 'Legacy Copyright')" :key="license.id">
                    <strong>{{ license.name }}:</strong> {{ license.description }}
                  </li>
                </ul>

                <h3 class="font-semibold">Public Domain</h3>
                <ul>
                  <li v-for="license in licenses.filter(l => l.type === 'Public Domain')" :key="license.id">
                    <strong>{{ license.name }}:</strong> {{ license.description }}
                  </li>
                </ul>
              </div>

            </div>

            <div v-if="selectedCreativeCommons" class="mb-6 w-64">

              <div v-if="selectedCreativeCommons === 8">
                <input class="hidden border border-gray-400 text-black font-semibold p-2 w-1/2 rounded-lg"
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
                <input class="border border-gray-400 text-black font-semibold p-2 w-1/2 rounded-lg"
                       type="number"
                       minlength="4"
                       maxlength="4"
                       v-model="selectedCopyrightYear">
              </div>

              <div v-if="form.errors.copyrightYear" v-text="form.errors.copyrightYear"
                   class="text-xs text-red-600 mt-1"></div>
            </div>
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
import { useForm } from "@inertiajs/inertia-vue3"
import { usePageSetup } from '@/Utilities/PageSetup'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import CancelButton from "@/Components/Global/Buttons/CancelButton"
import { computed, ref } from 'vue'
import LicensingExplained from '@/Components/Global/CreativeCommonsLicensing/LicensingExplained.vue'

usePageSetup('shows/slug/episodes/create')

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


const licenses = ref([
  { id: 1, type: 'Creative Commons', name: 'CC BY (Attribution)', description: 'Allows others to distribute, remix, adapt, and build upon your work, even commercially, as long as they credit you for the original creation.' },
  { id: 2, type: 'Creative Commons', name: 'CC BY-SA (Attribution-ShareAlike)', description: 'Similar to CC BY but requires derivatives to be licensed under identical terms.' },
  { id: 3, type: 'Creative Commons', name: 'CC BY-ND (Attribution-NoDerivs)', description: 'Allows for redistribution, commercial and non-commercial, as long as the content is passed along unchanged and in whole, with credit to you.' },
  { id: 4, type: 'Creative Commons', name: 'CC BY-NC (Attribution-NonCommercial)', description: 'Allows others to remix, tweak, and build upon your work non-commercially, and although their new works must also acknowledge you and be non-commercial, they don’t have to license their derivative works on the same terms.' },
  { id: 5, type: 'Creative Commons', name: 'CC BY-NC-SA (Attribution-NonCommercial-ShareAlike)', description: 'Lets others remix, tweak, and build upon your work non-commercially, as long as they credit you and license their new creations under the identical terms.' },
  { id: 6, type: 'Creative Commons', name: 'CC BY-NC-ND (Attribution-NonCommercial-NoDerivs)', description: 'Allows others to download your works and share them with others as long as they credit you, but they can’t change them in any way or use them commercially.' },
  { id: 7, type: 'Legacy Copyright', name: 'All Rights Reserved', description: 'The creator retains all the privileges provided by copyright law, such as the right to reproduce, distribute, and display the work publicly.' },
  { id: 8, type: 'Public Domain', name: 'No Copyright', description: 'The work is free for public use; anyone can use, modify, or share it without asking for permission or providing attribution.' }
]);

let submit = () => {
  if (form.video_embed_code && form.video_url) {
    addEmbedCodeConfirm();
  } else
    form.creative_commons_id = selectedCreativeCommons.value
    form.copyrightYear = selectedCopyrightYear.value
    form.post(route('showEpisodes.store', props.show.slug));
};

// let submit = () => {
//     form.patch(route('shows.showEpisodes.store'));
// };

// let showMessage = ref(true);

// function back() {
//     let urlPrev = usePage().props.value.urlPrev
//     if (urlPrev !== 'empty') {
//         Inertia.visit(urlPrev)
//     }
//     if (urlPrev === 'empty') {
//         Inertia.visit('/shows/'+props.show.slug+'/manage')
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