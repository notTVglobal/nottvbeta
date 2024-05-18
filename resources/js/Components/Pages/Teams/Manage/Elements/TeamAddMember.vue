<template>
  <Modal :show="teamStore.showModal" @close="teamStore.showModal = false">

    <template #header>
      Add a new team member
    </template>
    <template #default>

      <form @submit.prevent="submit">
        <input v-model="search"
               type="search"
               @input="searchCreators"
               placeholder="Search by name..."
               class="border px-2 rounded-lg mb-2"
        />
        <div class="h-32">
          <div v-for="creator in filteredCreators"
               :key="creator.id">
              <button @click.prevent="addTeamMember(creator)"
                      class="flex pb-1 cursor-pointer disabled:cursor-not-allowed"
                      :disabled="isUserOnTeam(creator)"
              >
                <img v-if="creator.profile_photo_path" :alt="creator.name"
                     :src="`/storage/${creator.profile_photo_path}`" class="rounded-full mr-3 h-10 w-10 object-cover">
                <img v-else-if="creator.profile_photo_url" :alt="creator.name" :src="creator.profile_photo_url"
                     class="rounded-full mr-3 h-10 w-10 object-cover">
                <span>{{ creator.name }}</span>

                <span v-if="creator.teams.includes(teamStore.id)"
                      class="text-xs text-white bg-green-800 uppercase justify-center items-center ml-3 top-1.5
                                            font-semibold inline-block h-1/2 py-0.5 px-1 rounded last:mr-0 mr-1">
                    team member
                  </span>
              </button>
          </div>
        </div>
        <div class="pt-4 font-semibold">
          Can't find the person above? Invite them below!
        </div>
        <div class="pb-2">
          Send an email invitation to join your team.
        </div>
        <div class="mb-3 bg-orange-300 py-1 px-2 text-xs font-semibold text-red-800">
          In development. Not currently working.
        </div>
        <div class="flex gap-2">
          <input
              type="email"
              placeholder="Email Address..."
              class="rounded flex-1"
          >
          <button class="bg-green-500 hover:bg-green-600 text-white rounded w-20 p-2">Invite</button>
        </div>
      </form>
    </template>
    <template #footer>
      <!--            <button @click="closeModal" class="text-blue-600 hover:text-gray-500">Cancel</button>-->
      <button @click="closeModal" class="bg-gray-500 hover:bg-gray-600 py-2 px-4 text-white rounded-lg mr-2">Cancel
      </button>
    </template>
  </Modal>

</template>

<script setup>
import { ref, watch } from 'vue'
import Modal from '@/Components/Global/Modals/Modal'
import throttle from 'lodash/throttle'
import { useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import { useTeamStore } from '@/Stores/TeamStore'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'

const teamStore = useTeamStore()

let props = defineProps({
  creators: Object,
  creatorFilters: Object,
})

let search = ref('')

const fetchCreators = async () => {
  try {
    const response = await axios.get('/api/creators'); // New endpoint to fetch all creators
    teamStore.setCreators(response.data);
  } catch (error) {
    console.error(error);
  }
};

watch(search, (value) => {
  searchCreators(value);
});
// watch(search, throttle(function (value) {
//   Inertia.get('/teams/' + teamStore.slug + '/manage', {search: value}, {
//     preserveState: true,
//     replace: true,
//   })
// }, 300))

function closeModal() {
  teamStore.showModal = false

  // tec21: this isn't working...
  // search = ''
}

function isUserOnTeam(creator) {
  return !!creator.teams.includes(teamStore.id)
}

let form = useForm({
  user_id: '',
  team_id: teamStore.id,
  team_slug: teamStore.slug,
})

async function addTeamMember(creator) {
  if (creator.teams.includes(teamStore.id)) {
    return alert(creator.name + ' is already on this team.')
  }

  form.user_id = creator.id
  teamStore.showModal = false
  submit()
  form.user_id = ''
}

function submit() {
  form.post(route('teams.addTeamMember'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      Inertia.visit(route('teams.manage', [teamStore.slug]), {
        method: 'get',
        preserveScroll: true,
      })
    },
  })
}

</script>
