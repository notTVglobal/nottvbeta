<template>

  <Head title="Add RSS Feed"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="min-h-screen bg-white text-black dark:bg-gray-800 dark:text-gray-50">

      <NewsHeader :can="can">Newsroom</NewsHeader>

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <div class="w-full overflow-hidden bg-white shadow-sm sm:rounded-lg py-5 max-w-3xl mx-auto">

      <div class="flex flex-row justify-between">
        <h2 class="text-xl font-semibold leading-tight">
          Add RSS Feed
        </h2>
        <div class="flex justify-end space-x-2">

        </div>
      </div>

      <div class="p-6 border-b border-gray-200">
        <form @submit.prevent="submit">
          <div class="mb-6">
            <label
                for="Name"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
            >Name</label
            >
            <input
                type="text"
                v-model="form.name"
                name="name"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                placeholder=""
            />
            <div
                v-if="form.errors.name"
                class="text-sm text-red-600"
            >
              {{ form.errors.name }}
            </div>
          </div>
          <div class="mb-6">
            <label
                for="Url"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
            >URL</label
            >
            <input
                type="text"
                v-model="form.url"
                name="url"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                placeholder=""
            />
            <div
                v-if="form.errors.url"
                class="text-sm text-red-600"
            >
              {{ form.errors.url }}
            </div>
          </div>

          <div class=" flex justify-start">
            <button
                type="submit"
                class="h-fit text-white bg-blue-700 hover:bg-blue-300 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 "
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

  </div>

</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import Button from "@/Jetstream/Button"
import Message from "@/Components/Global/Modals/Messages"
import CancelButton from '@/Components/Global/Buttons/CancelButton.vue'
import NewsHeader from '@/Components/Pages/News/NewsHeader.vue'

usePageSetup('newsFeedCreate')

const appSettingStore = useAppSettingStore()

const props = defineProps({
  can: Object,
});

let form = useForm({
  name: '',
  url: '',
});

let submit = () => {
  form.post(route("newsRssFeeds.store"));
};

</script>
