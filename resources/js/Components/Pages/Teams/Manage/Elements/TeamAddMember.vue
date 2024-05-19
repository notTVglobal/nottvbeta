<template>
  <Modal :show="teamStore.showModal" @close="teamStore.showModal = false">

    <template #header>
      Add a new team member
    </template>
    <template #default>

      <div>
        <input v-model="search"
               type="search"
               @input="searchCreators"
               placeholder="Search by name..."
               class="border px-2 rounded-lg mb-2"
        />
        <div class="h-32">
          <div v-for="creator in paginatedCreators" :key="creator.id" class="flex">
            <button @click.prevent="addTeamMember(creator)"
                    class="flex pb-1 cursor-pointer disabled:cursor-not-allowed"
                    :disabled="isUserOnTeam(creator)"
            >
              <img v-if="creator.profile_photo_path" :alt="creator.name"
                   :src="`/storage/${creator.profile_photo_path}`" class="rounded-full mr-3 h-10 w-10 object-cover">
              <img v-else-if="creator.profile_photo_url" :alt="creator.name" :src="creator.profile_photo_url"
                   class="rounded-full mr-3 h-10 w-10 object-cover">
              <span>{{ creator.name }}</span>
            </button>
            <div v-for="team in creator.teams" :key="team.id">
              <span v-if="team.id === teamStore.id && team.is_manager"
                    class="text-xs text-white bg-blue-800 uppercase justify-center items-center ml-3 top-1.5
                           font-semibold inline-block h-1/2 py-0.5 px-1 rounded last:mr-0 mr-1">
                manager
              </span>
              <span v-else-if="team.id === teamStore.id"
                    class="text-xs text-white bg-green-800 uppercase justify-center items-center ml-3 top-1.5
                           font-semibold inline-block h-1/2 py-0.5 px-1 rounded last:mr-0 mr-1">
                team member
              </span>
            </div>
          </div>
        </div>
        <div class="pt-4 font-semibold">
          Can't find the person above? Invite them below!
        </div>
        <div class="pb-2">
          Send an email invitation to join your team.
        </div>
        <div class="flex gap-2">
          <input
              v-model="inviteEmail"
              type="email"
              placeholder="Email Address..."
              class="rounded flex-1"
          >
          <button @click="sendInvite" class="bg-green-500 hover:bg-green-600 text-white rounded w-20 p-2">Invite</button>
        </div>
        <div class="message-container">
          <transition name="fade">
            <div v-if="successMessage" class="message bg-green-300 text-green-800">
              {{ successMessage }}
            </div>
          </transition>
          <transition name="fade">
            <div v-if="errorMessage" class="message bg-red-300 text-red-800">
              {{ errorMessage }}
            </div>
          </transition>
        </div>
      </div>
    </template>
    <template #footer>
      <!--            <button @click="closeModal" class="text-blue-600 hover:text-gray-500">Cancel</button>-->
      <button @click="closeModal" class="bg-gray-500 hover:bg-gray-600 py-2 px-4 text-white rounded-lg mr-2">Cancel
      </button>
    </template>
  </Modal>

</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue'
import Modal from '@/Components/Global/Modals/Modal'
import throttle from 'lodash/throttle'
import { useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import { useTeamStore } from '@/Stores/TeamStore'
import { useNotificationStore } from '@/Stores/NotificationStore'

const teamStore = useTeamStore()
const notificationStore = useNotificationStore()

let props = defineProps({
  creators: Object,
  creatorFilters: Object,
})

let search = ref('')
const currentPage = ref(1)
const pageSize = 3

const fetchCreators = async () => {
  try {
    const response = await axios.get('/api/all-creators') // New endpoint to fetch all creators
    teamStore.setCreators(response.data.data)
    // Debugging: Log the creators data to ensure it's populated correctly
    console.log('Creators fetched:', response.data.data)
    console.log('Team Id: ', teamStore.id)
  } catch (error) {
    console.error(error)
  }
}

const filteredCreators = computed(() => {
  if (!search.value) return teamStore.creators
  return teamStore.creators.filter(creator =>
      creator.name.toLowerCase().includes(search.value.toLowerCase()),
  )
})

const paginatedCreators = computed(() => {
  const start = (currentPage.value - 1) * pageSize
  return filteredCreators.value.slice(start, start + pageSize)
})

// const totalPages = computed(() => {
//   return Math.ceil(filteredCreators.value.length / pageSize)
// })
//
// const prevPage = () => {
//   if (currentPage.value > 1) {
//     currentPage.value--
//   }
// }
//
// const nextPage = () => {
//   if (currentPage.value < totalPages.value) {
//     currentPage.value++
//   }
// }

const inviteEmail = ref('');
const successMessage = ref('');
const errorMessage = ref('');

const clearMessages = () => {
  setTimeout(() => {
    successMessage.value = '';
    errorMessage.value = '';
  }, 3000); // Clear messages after 3 seconds
};

const sendInvite = async () => {
  console.log('Send Invite Function Called'); // Debugging log
  console.log(`Sending invite to: ${inviteEmail.value}`); // Debugging log
  console.log(`Endpoint: /teams/${teamStore.slug}/invite`); // Debugging log

  try {
    const response = await axios.post(`/teams/${teamStore.slug}/invite`, {
      email: inviteEmail.value,
    });

    console.log('Response:', response); // Debugging log

    successMessage.value = response.data.message;
    errorMessage.value = '';
    inviteEmail.value = ''; // Clear the input field
    clearMessages(); // Call clearMessages to clear the messages after a few seconds
  } catch (error) {
    console.log('Error:', error); // Debugging log
    if (error.response && error.response.data && error.response.data.message) {
      errorMessage.value = error.response.data.message;
    } else {
      errorMessage.value = 'An error occurred while sending the invitation.';
    }
    successMessage.value = '';
    clearMessages(); // Call clearMessages to clear the messages after a few seconds
  }
};
onMounted(() => {
  fetchCreators()
})

// watch(search, throttle(function (value) {
//   Inertia.get('/teams/' + teamStore.slug + '/manage', {search: value}, {
//     preserveState: true,
//     replace: true,
//   })
// }, 300))

function closeModal() {
  teamStore.showModal = false
  search.value = ''

  // tec21: this isn't working...
  // search = ''
}

function isUserOnTeam(creator) {
  return creator.teams.some(team => team.id === teamStore.id)
}

async function addTeamMember(creator) {
  if (isUserOnTeam(creator)) {
    notificationStore.setToastNotification(creator.name + ' is already on this team.', 'warning')
    return
  }

  const payload = {
    user_id: creator.id,
    team_id: teamStore.id,
  }

  teamStore.showModal = false

  try {
    const response = await axios.post(route('teams.addTeamMember'), payload)
    if (response.status === 200) {
      teamStore.addMember(response.data.member)
      teamStore.updateCreatorTeams(creator.id, teamStore.id) // Update the creator's teams in the store
      notificationStore.setToastNotification(creator.name + ' has been added to the team.', 'success')
    } else {
      notificationStore.setToastNotification('Failed to add ' + creator.name + ' to the team.', 'warning')
    }
  } catch (error) {
    console.error(error)
    notificationStore.setToastNotification('An error occurred while adding ' + creator.name + ' to the team.', 'error')
  }
}

// async function addTeamMember(creator) {
//   if (creator.teams.includes(teamStore.id)) {
//     return alert(creator.name + ' is already on this team.')
//   }
//
//   form.user_id = creator.id
//   teamStore.showModal = false
//   submit()
//   form.user_id = ''
// }
//
// function submit() {
//   form.post(route('teams.addTeamMember'), {
//     preserveScroll: true,
//     onSuccess: () => {
//       form.reset()
//       Inertia.visit(route('teams.manage', [teamStore.slug]), {
//         method: 'get',
//         preserveScroll: true,
//       })
//     },
//   })
// }

</script>
<style scoped>
.message-container {
  height: 20px; /* Adjust this height as needed */
  display: flex;
  align-items: center;
  justify-content: center;
}

.message {
  margin-top: 16px;
  padding: 5px;
  font-size: 14px;
  font-weight: bold;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 1s ease-in-out;
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  opacity: 0;
}
</style>