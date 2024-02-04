<template>

  <Head :title="`Upload Movie`"/>

  <header id="topDiv" class="">

    <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

    <div
        class="flex justify-between p-4 m-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800"
        role="alert"
        v-if="props.errors.video">
                <span class="font-medium">
                    {{ props.errors.video }}
                </span>
    </div>

  </header>


  <div class="place-self-center flex flex-col gap-y-3">
    <div class="bg-white text-black p-5 mb-10">

      <div class="flex justify-between mb-3">
        <div class="mb-4">
          <h1 class="text-3xl font-semibold">Add a Movie</h1>
        </div>
        <div>
          <div class="flex flex-wrap-reverse justify-end gap-2">
            <CancelButton/>
          </div>
        </div>
      </div>

      <div>
        <form @submit.prevent="submit" enctype="multipart/form-data">
          <div class="bg-orange-800 text-white p-4 mb-6"><span class="font-semibold">Note:</span> File uploads are
            limited to 500 MB until we implement a chunk method.
            TO DO: process video uploads (transcode and encrypt) using FFMPEG then upload it to our destination(s),
            whether that is a cloud service, an attached volume, remote server,
            or a peer-to-peer distribution network.<br>
            <!--                    For more info about chunking go to:-->
            <!--                        <a href="https://github.com/pionl/laravel-chunk-upload" class="text-blue-400 hover:text-blue-200" target="_blank">-->
            <!--                            https://github.com/pionl/laravel-chunk-upload</a>-->
            <!--                        <br><br>-->
            <!--                        Also look at: <a href="https://pineco.de/chunked-file-upload-with-laravel-and-vue/" class="text-blue-400 hover:text-blue-200" target="_blank">-->
            <!--                            https://pineco.de/chunked-file-upload-with-laravel-and-vue/-->
            <!--                        </a>-->
          </div>
          <input
              v-model="form.name"
              type="text"
              name="name"
              id="name"
              class="border border-gray-400 rounded w-full px-2 py-2 my-2"
              placeholder="Movie Title"
          />
          <div v-if="form.errors.name" v-text="form.errors.name"
               class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
          <input
              v-model="form.logline"
              type="text"
              name="logline"
              id="logline"
              class="border border-gray-400 rounded w-full px-2 py-2 my-2"
              placeholder="Logline"
          />
          <div v-if="form.errors.logline" v-text="form.errors.logline"
               class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
          <textarea
              v-model="form.description"
              type="text"
              name="description"
              id="description"
              cols="30"
              row="20"
              class="border border-gray-400 rounded w-full px-2 py-2 my-2"
              placeholder="Description"
          />
          <div v-if="form.errors.description" v-text="form.errors.description"
               class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>
          <input
              v-model="form.file_url"
              type="text"
              name="file_url"
              id="file_url"
              class="border border-gray-400 rounded w-full px-2 py-2 my-2"
              placeholder="Link to existing video file (optional)"
          />
          <div v-if="form.errors.file_url" v-text="form.errors.file_url"
               class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>

          <div v-if="message" v-text="message"
               class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>

          <div class=" flex justify-start">
            <button
                @click="submit"
                class="bg-green-600 hover:bg-green-500 text-white rounded py-2 px-4 mt-2"
                :disabled="form.processing"
            >
              Save
            </button>
            <JetValidationErrors class="ml-4"/>
          </div>

        </form>

        <!--                    <div class="border-t border-gray-800 py-8 mt-8">-->

        <!--                    <div-->
        <!--                        @dragenter.prevent="toggleActive"-->
        <!--                        @dragleave.prevent="toggleActive"-->
        <!--                        @dragover.prevent-->
        <!--                        @drop.prevent="drop"-->
        <!--                        :class="{ 'active-dropzone': active }"-->
        <!--                        class="dropzone mt-4">-->
        <!--                        <span>Drag or Drop Video</span>-->
        <!--                        <span>OR</span>-->
        <!--                        <label for="dropzoneFile" class="cursor-pointer hover:bg-gray-600">Select Video</label>-->
        <!--                        <input-->
        <!--                            type="file"-->
        <!--                            name="video"-->
        <!--                            id="dropzoneFile"-->
        <!--                            class="dropzoneFile border border-gray-400 rounded w-full px-2 py-2 my-2"-->
        <!--                            @change="selectedFile"-->
        <!--                            ref="fileInput"-->
        <!--                            accept="video/*"-->
        <!--                            @input="form.video = $event.target.files[0]"-->
        <!--                            style="display: none"/>-->
        <!--                    </div>-->

        <!--                    <div class="mt-2">File: {{ dropzoneFile.name }}</div>-->

        <!--                    <div v-if="form.errors.video" v-text="form.errors.video"-->
        <!--                         class="bg-red-600 p-2 w-full text-white font-semibold mt-1"></div>-->


        <!--                    </div>-->


      </div>



      <section>
        <div class="flex w-fit mx-auto px-6 py-1 my-6 bg-gray-300 text-black font-semibold justify-center">A NOTE TO THE DEVELOPERS:</div>
        <div class="w-fit mx-auto px-6 py-1 my-6 bg-gray-100 text-black justify-center space-y-4">
          <h1>Designing an Effective Movie Database</h1>
          <h2>For Users: Cross-Category Accessibility</h2>
          <ul>
            <li><strong>Multiple Tags/Labels:</strong> Films can be tagged with multiple categories and sub-categories.</li>
            <li><strong>Advanced Search and Filtering:</strong> Implement a robust system for users to find films based on multiple criteria.</li>
            <li><strong>Recommendation System:</strong> A recommendation algorithm that suggests films based on user preferences.</li>
            <li><strong>User-Centric Interface:</strong> Design the UI to showcase movies in multiple relevant categories.</li>
          </ul>

          <h2>For Film Producers: Categorization Process</h2>
          <ul>
            <li><strong>Primary Category Selection:</strong> Producers choose a primary category for their film.</li>
            <li><strong>Secondary/Tertiary Tags:</strong> Option to select additional categories or sub-categories.</li>
            <li><strong>Automated Suggestions:</strong> AI-driven tool for suggesting additional categorizations.</li>
            <li><strong>Editorial Oversight:</strong> Reviews by editors or curators for accurate classification.</li>
            <li><strong>Feedback Loop:</strong> Producers receive analytics on film discovery and viewing.</li>
          </ul>

          <h2>Balancing Database Structure with User Experience</h2>
          <ul>
            <li><strong>Avoid Over-Categorization:</strong> Maintain a balance between comprehensiveness and simplicity.</li>
            <li><strong>Regular Updates and Maintenance:</strong> Periodically review the categorization system for relevance.</li>
            <li><strong>User Feedback:</strong> Collect and analyze user feedback for system improvements.</li>
          </ul>
        </div>
      </section>



    </div>
  </div>

</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import Message from '@/Components/Global/Modals/Messages'
import CancelButton from '@/Components/Global/Buttons/CancelButton'

usePageSetup('movies/create')

const appSettingStore = useAppSettingStore()

let props = defineProps({
  errors: ref(''),
  isHidden: ref(false),
  // filters: Object,
})

let form = useForm({
  name: '',
  description: '',
  video: '',
  file_url: '',
})

let save = () => {
  axios.post(route('movies.store', form.data))
}

let submit = () => {
  // form.append('form', json);
  // axios.post("/api/movies/upload", form.data);
  form.post(route('movies.store', form))
}

let dropzoneFile = ref([])

const active = ref(false)

const toggleActive = () => {
  active.value = !active.value
}

const drop = (e) => {
  dropzoneFile.value = e.dataTransfer.files[0]
  active.value = !active.value
}

const selectedFile = () => {
  dropzoneFile.value = document.querySelector('.dropzoneFile').files[0]
}

// Dropzone tutorial: https://www.youtube.com/watch?v=wWKhKPN_Pmw


</script>

<style scoped>
.dropzone {
  width: 400px;
  height: 200px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  row-gap: 16px;
  border: 2px dashed #6b7280;
  transition: 0.3s ease all;
}

label {
  padding: 8px 12px;
  color: #fff;
  background-color: #4bb1b1;
  transition: 0.3s ease all;
}

.active-dropzone {
  color: #fff;
  border-color: #fff;
  background-color: #4bb1b1;
}

.active-dropzone label {
  background-color: #fff;
  color: #4bb1b1;
}


</style>
