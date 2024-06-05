<template>
  <Head title="Create Show"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <div class="flex justify-between mt-3 mb-6">
        <div class="text-3xl">Create Show</div>
        <div>
          <CancelButton/>
        </div>
      </div>

      <form @submit.prevent="submit" class="max-w-md mx-auto mt-8">


        <CreateShowSelectTeam :teams="teams" :errors="form.errors"/>


        <CreateShowSelectShowRunner :defaultShowRunnerId="creatorId"
                                    :teamMembers="teamMembers"
                                    @selectedShowRunnerCreatorId="selectedShowRunnerCreatorIdHandler"
                                    :errors="form.errors"/>

        <CreateShowSetShowName :errors="form.errors"/>


        <CreateShowSetCategories :errors="form.errors"
                                 :categories="categories" />


        <CreateShowSetDescription :errors="form.errors"/>


        <SocialMediaLinksStoreUpdateForForm v-model:form="form"/>

        <div class="mb-6">
          <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
                 for="notes"
          >
            Notes (Only your team members see these notes, they are not public)
          </label>
          <textarea v-model="form.notes"
                    class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    type="text"
                    name="notes"
                    id="notes"
          ></textarea>
          <div v-if="form.errors.notes" v-text="form.errors.notes" class="text-xs text-red-600 mt-1"></div>
        </div>

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
        </div>

      </form>

      <CheckboxNotification/>

    </div>
  </div>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useNotificationStore } from '@/Stores/NotificationStore'
import { useTeamStore } from '@/Stores/TeamStore'
import { useShowStore } from '@/Stores/ShowStore'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import CheckboxNotification from '@/Components/Global/Modals/CheckboxNotification'
import CancelButton from '@/Components/Global/Buttons/CancelButton'
import Message from '@/Components/Global/Modals/Messages'
import SocialMediaLinksStoreUpdateForForm from '@/Components/Global/SocialMedia/SocialMediaLinksStoreUpdateForForm.vue'
import CreateShowSelectTeam from '@/Components/Pages/Shows/Elements/CreateShowSelectTeam.vue'
import CreateShowSelectShowRunner from '@/Components/Pages/Shows/Elements/CreateShowSelectShowRunner.vue'
import CreateShowSetShowName from '@/Components/Pages/Shows/Elements/CreateShowSetShowName.vue'
import CreateShowSetCategories from '@/Components/Pages/Shows/Elements/CreateShowSetCategories.vue'
import CreateShowSetDescription from '@/Components/Pages/Shows/Elements/CreateShowSetDescription.vue'

usePageSetup('showsCreate')

const appSettingStore = useAppSettingStore()
const notificationStore = useNotificationStore()
const teamStore = useTeamStore()
const showStore = useShowStore()

let props = defineProps({
  teams: Object,
  userId: Number,
  creatorId: Number,
  categories: Object,
})

onMounted(() => {
  // selectedShowRunnerCreatorId.value = defaultShowRunnerId.value
  showStore.categories = props.categories
  // showStore.initializeDescriptions(selectedCategoryId.value, selectedSubCategoryId.value)
  showStore.selectedTeamId = defaultTeamId.value
})

const defaultTeamId = computed(() => {
  return teamStore.team.id || (props.teams.length > 0 ? props.teams[0].id : null)
})

// const defaultShowRunnerId = computed(() => {
//   return props.creatorId
// })

// Reactive property for the selected show_runner ID
const selectedShowRunnerCreatorId = ref(props.defaultShowRunnerId);
const selectedShowRunnerCreatorIdHandler = (id) => {
  selectedShowRunnerCreatorId.value = id;
};

// fetch Team Members
const teamMembers = ref([]) // Store team members locally in the component

// Fetch team members when the selected team changes
const fetchTeamMembers = async () => {
  if (defaultTeamId.value) {
    try {
      const response = await axios.post(`/api/fetch-team-members`, {teamId: defaultTeamId.value})
      teamMembers.value = response.data
    } catch (error) {
      console.error('Error fetching team members:', error)
    }
  }
}

// Use watch to react to changes in defaultTeamId computed property
watch(defaultTeamId, fetchTeamMembers, { immediate: true }) // immediate: true to run on mount

let form = useForm({
  name: '',
  description: '',
  user_id: props.userId,
  team_id: defaultTeamId.value,
  category: '',
  sub_category: '',
  www_url: '',
  instagram_name: '',
  telegram_url: '',
  twitter_handle: '',
  notes: '',
  show_runner_creator_id: '',
})

// let showCategoryDescription = ref(null)

const checkForTeams = () => {
  if (props.teams.length === 0) {
    // Perform some actions if data array is empty
    console.log('No teams available.')
    notificationStore.active = true
    notificationStore.title = 'No teams available.'
    notificationStore.body = 'Please create a team before you create a show.'
    notificationStore.buttonLabel = 'OKAY'
    notificationStore.onClickAction = 'redirect'
    notificationStore.uri = '/shows/create'
    notificationStore.redirectPageUri = '/teams/create'
    // Additional logic for empty array
  } else {
    // Do nothing if data array is not empty
    console.log('Teams are available.')
  }
}

onMounted(() => {
  checkForTeams()
})

let submit = () => {
  form.name = showStore.name
  form.description = showStore.description
  form.category = showStore.category_id
  form.sub_category = showStore.sub_category_id
  form.team_id = showStore.selectedTeamId
  form.show_runner_creator_id = selectedShowRunnerCreatorId.value;
  form.post('/shows')
}

// function chooseCategory(event) {
//   showCategoryDescription = props.categories[event.target.selectedIndex].description
// }

function reset() {
  form.reset()
}

onBeforeUnmount(() => {
  showStore.reset()
})

</script>

<style scoped>
.status-1 {
  color: green; /* Example color for status ID 1 */
}

.status-2 {
  color: blue; /* Example color for status ID 2 */
}

.status-3 {
  color: purple; /* Example color for status ID 3 */
}

.status-4 {
  color: orange; /* Example color for status ID 4 */
}

.status-5 {
  color: red; /* Example color for status ID 4 */
}

.status-6 {
  color: darkgray; /* Example color for status ID 4 */
  font-style: italic;
}

.status-7 {
  color: black; /* Example color for status ID 4 */
  font-style: italic;
}

.status-8 {
  color: black; /* Example color for status ID 4 */
  font-style: italic;
}

.status-9 {
  color: red; /* Example color for status ID 4 */
  font-style: italic;
}

.status-10 {
  color: red; /* Example color for status ID 4 */
  font-style: italic;
}

.status-11 {
  color: darkgray; /* Example color for status ID 4 */
}
</style>
