<template>
  <Head title="Create Team"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <div class="flex justify-between mt-3 mb-6 pt-6">
        <div class="text-3xl">Create New Team</div>
        <div>
          <CancelButton/>
        </div>
      </div>

      <form @submit.prevent="submit" class="max-w-md mx-auto mt-8">
        <div class="mb-6">
          <label class="block mb-2 uppercase font-bold text-gray-700"
                 for="name"
          >
            Team Name <span class="text-red-500">* REQUIRED</span>
          </label>

          <input v-model="form.name"
                 class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 "
                 type="text"
                 name="name"
                 id="name"
                 required
                 placeholder="Team Name"
          >
          <div v-if="form.errors.name" v-text="form.errors.name" class="text-xs text-red-600 mt-1"></div>
        </div>

        <div class="mb-6">
          <label class="block mb-2 uppercase font-bold text-gray-700"
                 for="description"
          >
            Description (optional)
          </label>

          <textarea v-model="form.description"
                    class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    type="text"
                    name="description"
                    id="description"
                    placeholder="Description"
          ></textarea>
          <div v-if="form.errors.description" v-text="form.errors.description" class="text-xs text-red-600 mt-1"></div>
        </div>

        <div class="mb-6">
          <label class="block mb-2 uppercase font-bold text-gray-700"
                 for="totalSpots"
          >
            Maximum # of Team Members <span class="text-red-500">* REQUIRED</span>
          </label>
          <input v-model="form.totalSpots"
                 class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 "
                 type="text"
                 name="totalSpots"
                 id="totalSpots"
          />
          <div v-if="form.errors.totalSpots" v-text="form.errors.totalSpots" class="text-xs text-red-600 mt-1"></div>
        </div>

        <SocialMediaLinksStoreUpdateForForm v-model:form="form" />

        <input v-model="form.user_id" hidden>
        <div class="flex justify-between mb-6">
          <JetValidationErrors class="mr-4"/>
          <button
              type="submit"
              class="h-fit bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4"
              :disabled="form.processing"
          >
            Submit
          </button>
          <div @click="reset" class="text-blue-600 text-sm cursor-pointer">Reset</div>
        </div>

      </form>

    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import Message from '@/Components/Global/Modals/Messages'
import CancelButton from '@/Components/Global/Buttons/CancelButton'
import SocialMediaLinksStoreUpdateForForm from '@/Components/Global/SocialMedia/SocialMediaLinksStoreUpdateForForm.vue'

usePageSetup('teams/create')

const appSettingStore = useAppSettingStore()

let props = defineProps({
  user: Object,
})

let form = useForm({
  name: '',
  description: '',
  user_id: props.user.id,
  totalSpots: '1',
  www_url: '',
  instagram_name: '',
  telegram_url: '',
  twitter_handle: '',
})

let submit = () => {
  form.post('/teams')
}

function reset() {
  form.reset()
}

</script>
