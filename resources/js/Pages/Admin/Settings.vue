<template>

  <Head :title="`Administrative Settings`"/>

  <div id="topDiv" class="place-self-center flex flex-col gap-y-3">
    <div
        class="w-fit lg:w-3/4 left-0 right-0 mx-auto bg-white dark:bg-gray-800 rounded text-black dark:text-gray-50 mt-6 p-5">

      <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

      <header>
        <div class="flex justify-between mb-3">
          <div class="mb-4">
            <h1 class="text-3xl font-semibold">Administrative Settings</h1>
          </div>
          <div>
            <div>
              <button
                  @click="userStore.btnRedirect('/dashboard')"
                  class="bg-black hover:bg-gray-800 text-white font-semibold ml-2 mt-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
              >Dashboard
              </button>
            </div>
          </div>
        </div>
      </header>

      <ServerTime/>

      <div class="bg-gray-300 dark:bg-gray-900 rounded pb-8 p-3 mb-6 mx-2 border-b border-2">
        <div class="font-semibold text-xl pb-2">Administrator only links</div>
        <div class="flex flex-wrap md:flex-row justify-items-start gap-2">
          <button
              @click="userStore.btnRedirect(`/users`)"
              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 col-span-1 rounded disabled:bg-gray-400"
          >All Users
          </button>
          <button
              @click="userStore.btnRedirect(`/admin/episodes`)"
              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 col-span-1 rounded disabled:bg-gray-400"
          >All Episodes
          </button>
          <button
              @click="userStore.btnRedirect(`/admin/shows`)"
              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 col-span-1 rounded disabled:bg-gray-400"
          >All Shows
          </button>
          <button
              @click="userStore.btnRedirect(`/admin/teams`)"
              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 col-span-1 rounded disabled:bg-gray-400"
          >All Teams
          </button>
          <button
              @click="userStore.btnRedirect(`/admin/channels`)"
              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 rounded disabled:bg-gray-400"
          >All Channels
          </button>
          <button
              @click="userStore.btnRedirect(`/admin/mistServerApi`)"
              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 rounded disabled:bg-gray-400"
          >MistServer API
          </button>
          <a
              :href="`http://`+props.mist_server_ip+`:4242`" target="_blank"
              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 rounded disabled:bg-gray-400"
          >MistServer Interface</a>
          <button
              @click="userStore.btnRedirect(`/admin/images`)"
              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 rounded disabled:bg-gray-400"
          >Images
          </button>
          <button
              @click="userStore.btnRedirect(`/videoupload`)"
              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 rounded disabled:bg-gray-400"
          >Video Upload
          </button>
          <button
              @click="userStore.btnRedirect(`/movies/create`)"
              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
          >Add a Movie
          </button>
          <button
              @click="userStore.btnRedirect(`/admin/invite_codes`)"
              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
          >Invite Codes
          </button>
          <button
              @click="getEpisodesFromEmbedCodes"
              class="bg-blue-600 hover:bg-blue-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400 disabled:no-cursor"
              :disabled="!getAllEpisodesButtonActive"
          >Get All Episode Videos From Embed Codes
          </button>
          <!--                    <Link-->
          <!--                        :href="`/admin/phpmyinfo`"><button-->
          <!--                        class="bg-blue-600 hover:bg-blue-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"-->
          <!--                    >phpinfo()</button>-->
          <!--                    </Link>-->

        </div>

      </div>

      <div>
        <form @submit.prevent="submit">
          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                   for="cdn_endpoint"
            >
              CDN ENDPOINT
            </label>

            <input v-model="form.cdn_endpoint"
                   class="border border-gray-400 p-2 w-full rounded-lg text-black"
                   type="text"
                   name="cdn_endpoint"
                   id="cdn_endpoint"
            >
            <div v-if="form.errors.cdn_endpoint" v-text="form.errors.cdn_endpoint"
                 class="text-xs text-red-600 mt-1"></div>
          </div>
          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                   for="cloud_folder"
            >
              CLOUD FOLDER
            </label>

            <div class="flex flex-row">
              <span class="pt-2 mr-2">/ </span>
              <input v-model="form.cloud_folder"
                     class="border border-gray-400 p-2 w-full rounded-lg text-black"
                     type="text"
                     name="cloud_folder"
                     id="cloud_folder"
              >
            </div>
            <span
                class="text-xs">NOTE: The forward slash is already entered in the backend. Just type the folder name.</span>

            <div v-if="form.errors.cloud_folder" v-text="form.errors.cloud_folder"
                 class="text-xs text-red-600 mt-1"></div>
          </div>
          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                   for="cloud_folder"
            >
              FIRST PLAY VIDEO SOURCE
            </label>

            <div class="flex flex-row">
              <input v-model="form.first_play_video_source"
                     class="border border-gray-400 p-2 w-full rounded-lg text-black"
                     type="text"
                     name="first_play_video_source"
                     id="first_play_video_source"
              >
            </div>
            <span class="text-xs"></span>

            <div v-if="form.errors.first_play_video_source" v-text="form.errors.first_play_video_source"
                 class="text-xs text-red-600 mt-1"></div>
          </div>
          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                   for="cloud_folder"
            >
              FIRST PLAY VIDEO SOURCE TYPE
            </label>

            <div class="flex flex-row">
              <input v-model="form.first_play_video_source_type"
                     class="border border-gray-400 p-2 w-full rounded-lg text-black"
                     type="text"
                     name="first_play_video_source"
                     id="first_play_video_source"
              >
            </div>
            <span class="text-xs">e.g., video/mp4 or application/x-mpegURL</span>

            <div v-if="form.errors.first_play_video_source" v-text="form.errors.first_play_video_source"
                 class="text-xs text-red-600 mt-1"></div>
          </div>
          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                   for="cloud_folder"
            >
              VIDEO NAME
            </label>

            <div class="flex flex-row">
              <input v-model="form.first_play_video_name"
                     class="border border-gray-400 p-2 w-full rounded-lg text-black"
                     type="text"
                     name="first_play_video_name"
                     id="first_play_video_name"
              >
            </div>
            <span class="text-xs"></span>

            <div v-if="form.errors.first_play_video_name" v-text="form.errors.first_play_video_name"
                 class="text-xs text-red-600 mt-1"></div>
          </div>
          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                   for="cloud_folder"
            >
              CHANNEL ID
            </label>

            <div class="flex flex-row">
              <input v-model="form.first_play_channel_id"
                     class="border border-gray-400 p-2 w-full rounded-lg text-black"
                     type="text"
                     name="first_play_channel_id"
                     id="first_play_channel_id"
              >
            </div>
            <span class="text-xs text-orange-500">This needs to become a dropdown of channels to select.</span>

            <div v-if="form.errors.first_play_channel_id" v-text="form.errors.first_play_channel_id"
                 class="text-xs text-red-600 mt-1"></div>
          </div>
          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                   for="mist_server_ip"
            >
              MIST SERVER IP ADDRESS
            </label>

            <div class="flex flex-row">
              <input v-model="form.mist_server_ip"
                     class="border border-gray-400 p-2 w-full rounded-lg text-black"
                     type="text"
                     name="mist_server_ip"
                     id="mist_server_ip"
              >
            </div>
            <div v-if="form.errors.mist_server_ip" v-text="form.errors.mist_server_ip"
                 class="text-xs text-red-600 mt-1"></div>
          </div>
          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                   for="mist_server_api_url"
            >
              MIST SERVER API URL
            </label>

            <div class="flex flex-row">
              <input v-model="form.mist_server_api_url"
                     class="border border-gray-400 p-2 w-full rounded-lg text-black"
                     type="text"
                     name="mist_server_api_url"
                     id="mist_server_api_url"
              >
            </div>
            <div v-if="form.errors.mist_server_api_url" v-text="form.errors.mist_server_api_url"
                 class="text-xs text-red-600 mt-1"></div>
          </div>
          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                   for="mist_server_username"
            >
              MIST SERVER USERNAME
            </label>

            <div class="flex flex-row">
              <input v-model="form.mist_server_username"
                     class="border border-gray-400 p-2 w-full rounded-lg text-black"
                     type="text"
                     name="mist_server_username"
                     id="mist_server_username"
              >
            </div>
            <div v-if="form.errors.mist_server_username" v-text="form.errors.mist_server_username"
                 class="text-xs text-red-600 mt-1"></div>
          </div>
          <div class="mb-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                   for="mist_server_password"
            >
              MIST SERVER PASSWORD
            </label>

            <div class="flex flex-row">
              <input v-model="form.mist_server_password"
                     class="border border-gray-400 p-2 w-full rounded-lg text-black"
                     type="password"
                     name="mist_server_password"
                     id="mist_server_password"
              >
            </div>
            <div v-if="form.errors.mist_server_password" v-text="form.errors.mist_server_password"
                 class="text-xs text-red-600 mt-1"></div>
          </div>

          <div class="flex justify-end my-6 mr-6">
            <JetValidationErrors class="mr-4"/>
            <button
                @click="submit"
                class="bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4"
                :disabled="form.processing"
            >
              Save
            </button>
          </div>

        </form>


        <div class="w-full flex flex-wrap mb-4 p-2 bg-amber-800 text-white">
          <span>Change Max Video Upload Size. convert Bytes to KB, MB, GB, TB.</span>
          <span>Update Video Uploader with the size.</span>
        </div>

        <div class="w-full flex flex-wrap mb-4 p-2 bg-amber-800 text-white">
          Content licenses
        </div>

        <div class="w-full flex flex-wrap mb-4 p-2 bg-amber-800 text-white">
          Episode Statuses
        </div>

        <div class="w-full flex flex-wrap mb-4 p-2 bg-amber-800 text-white">
          Show Statuses
        </div>

        <div class="w-full flex flex-wrap mb-4 p-2 bg-amber-800 text-white">
          Channels. Display list of channels. Click channel name to go to the channel playlist edit page.
          Grid style, 1 column mobile, 2 column tablet, 3 column lg, 4 column xl
          Rows: Times (next 24 hours) --> can scroll up to 72 hours ahead or 72 hours back.
          Columns: Days (next 7 days). Need new Pages folder: Channels/Index, Channels/Create, Channels/Edit,
          ch/channelId (e.g., ch/01).

        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { Inertia } from "@inertiajs/inertia"
import { onBeforeMount, onMounted, ref } from "vue"
import { useForm } from "@inertiajs/inertia-vue3"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()
import { useUserStore } from "@/Stores/UserStore"
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import ServerTime from "@/Components/Pages/Admin/ServerTime"
import Message from "@/Components/Global/Modals/Messages"

const videoPlayerStore = useVideoPlayerStore()
const userStore = useUserStore()

let props = defineProps({
  id: Number,
  cdn_endpoint: String,
  cloud_folder: String,
  first_play_video_source: String,
  first_play_video_source_type: String,
  first_play_video_name: String,
  first_play_channel_id: String,
  mist_server_ip: String,
  mist_server_api_url: String,
  mist_server_username: String,
  mist_server_password: String,
  messageType: String,
});

let form = useForm({
  id: props.id,
  cdn_endpoint: props.cdn_endpoint,
  cloud_folder: props.cloud_folder,
  first_play_video_source: props.first_play_video_source,
  first_play_video_source_type: props.first_play_video_source_type,
  first_play_video_name: props.first_play_video_name,
  mist_server_ip: props.mist_server_ip,
  mist_server_api_url: props.mist_server_api_url,
  mist_server_username: props.mist_server_username,
  mist_server_password: props.mist_server_password,
})

let submit = () => {
  form.put(route('admin.settings'));
};

let getAllEpisodesButtonActive = ref(false);

userStore.currentPage = 'adminSettings'
userStore.showFlashMessage = true;

onMounted(async () => {
  videoPlayerStore.makeVideoTopRight();
  if (userStore.isMobile) {

    appSettingStore.ott = 0
appSettingStore.pageIsHidden = false
  }
  document.getElementById("topDiv").scrollIntoView()
});

function getEpisodesFromEmbedCodes() {
  Inertia.post('getVideosFromEmbedCodes')
  getAllEpisodesButtonActive = false;
}

</script>
