<template>

  <Head title="Create News Post"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <div class="flex flex-row justify-between mt-6">
        <h2 class="text-xl font-semibold leading-tight">
          Create News Post
        </h2>
        <div class="flex justify-end space-x-2">

        </div>
      </div>

      <div class="p-6 border-b border-gray-200">
        <form @submit.prevent="submit">
          <div class="mb-6">
            <label
                for="Title"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
            >Title</label
            >
            <input
                type="text"
                v-model="form.title"
                name="title"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                placeholder=""
            />
            <div
                v-if="form.errors.title"
                class="text-sm text-red-600"
            >
              {{ form.errors.title }}
            </div>
          </div>
          <div class="mb-6">
            <label
                for="content_json"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
            >Content</label>
            <tiptap v-if="appSettingStore.currentPage === 'newsCreate'"/>
            <!--                        <textarea-->
            <!--                            type="text"-->
            <!--                            v-model="form.content_json"-->
            <!--                            id=""-->
            <!--                            name="content_json"-->
            <!--                            rows=10-->
            <!--                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"-->
            <!--                        ></textarea>-->

            <div
                v-if="form.errors.content_json"
                class="text-sm text-red-600"
            >
              {{ form.errors.content_json }}
            </div>
          </div>
          <div class=" flex justify-start">
            <button
                type="submit"
                class="h-fit text-white bg-blue-700  focus:outline-none  font-medium rounded-lg text-sm px-5 py-2.5 "
                :disabled="form.processing"
                :class="{ 'opacity-25': form.processing }"
            >
              Submit
            </button>
            <CancelButton />
            <JetValidationErrors class="ml-4"/>
          </div>
        </form>
      </div>


    </div>

  </div>

</template>

<script setup>
import { onBeforeMount } from "vue"
import { useForm } from '@inertiajs/inertia-vue3'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useNewsStore } from "@/Stores/NewsStore"
import JetValidationErrors from '@/Jetstream/ValidationErrors'
// import TabbableTextarea from "@/Components/Global/TextEditor/TabbableTextarea"
import Tiptap from "@/Components/Global/TextEditor/TiptapNewsStoryCreate"
// import Tiptap from "@/Components/Global/TextEditor/TiptapNewsStoryEdit"
import Message from "@/Components/Global/Modals/Messages"
import CancelButton from '@/Components/Global/Buttons/CancelButton.vue'

usePageSetup('newsCreate')

const appSettingStore = useAppSettingStore()
const newsStore = useNewsStore()

let props = defineProps({
  can: Object,
});

let form = useForm({
  title: '',
  body: '',
  content_json: [],
});

let submit = () => {
  form.body = newsStore.newsArticleContentTiptop;
  form.post(route("newsStory.store"));
};

onBeforeMount(() => {
  // userStore.scrollToTopCounter = 0;
  newsStore.newsArticleContentTiptop = '';
})

</script>
