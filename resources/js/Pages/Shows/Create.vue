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

      <div class="bg-orange-700 text-white w-full p-6"><span class="font-bold">NOTE: </span>
        We are working on an episode poster and video uploader for this page. For the time being, please
        go to the show <span class="font-bold">EDIT</span> page after you create the show to add a video and a poster.
      </div>

      <form @submit.prevent="submit" class="max-w-md mx-auto mt-8">
        <div class="mb-6">
          <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
                 for="team"
          >
            Team
          </label>
          <select
              class="border border-gray-400 p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs text-gray-800"
              v-model="selectedTeamId"
              required
          >
            <option disabled value="">Select Team</option>
            <option
                v-for="team in props.teams"
                :key="team.id"
                :value="team.id"
                class="bg-white text-black border-b dark:text-gray-50 dark:bg-gray-800 dark:border-gray-600"
                :class="'status-' + team.status.id"
            >
              {{ team.name }} ({{ team.status.status }})
            </option>

          </select>


          <div v-if="form.errors.team_id" v-text="form.errors.team_id" class="text-xs text-red-600 mt-1"></div>
        </div>

        <div class="mb-6 border-2 p-3">
          <label class="block mb-2 uppercase font-bold dark:text-gray-200"
                 for="show_runner_creator_id"
          >
            Show Runner <span class="text-red-500">* REQUIRED</span>
          </label>
          <select
              class="border border-gray-400 p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs text-gray-800"
              v-model="selectedShowRunnerCreatorId"
              required
          >
            <option disabled value="">Select Show Runner</option>
            <option
                v-for="member in teamMembers"
                :key="member.creator_id"
                :value="member.creator_id"
                class="bg-white text-black border-b dark:text-gray-50 dark:bg-gray-800 dark:border-gray-600"
            >
              {{ member.name }}
            </option>
          </select>

          <!-- Button to toggle the explanation -->
          <button @click.prevent="toggleShowRunnerInfo"
                  class="btn mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold uppercase text-xs">
            👉 Who is a Show Runner?
          </button>

          <!-- Conditional rendering of the show runner explanation -->
          <div v-if="showRunnerInfoVisible" class="mt-2 text-gray-800 dark:text-gray-200">
            <div class="mb-2">
              <strong>Show Runner:</strong> The chief architect behind a show, responsible for overseeing every element
              of production. Comparable to an event planner, but for new media production, the show runner handles the
              creative vision and daily operations, ensuring the show’s vision is realized through managing everything
              from scriptwriting to final edits. They lead the production team, make critical decisions on content and
              direction, and maintain the show's consistency and quality across episodes.
            </div>
            <div>
              <div class="mt-2 text-gray-800 dark:text-gray-200">
                <h3 class="font-bold">Show Runner for Informal and Community-Driven Productions:</h3>
                <p class="mt-1">
                  A show runner in informal settings acts much like an event coordinator, emphasizing flexibility and
                  audience engagement. They adapt quickly to live interactions and maintain the creative vision,
                  ensuring the production is engaging and cohesive. Key responsibilities include:
                </p>
                <ul class="list-disc pl-5 mt-1">
                  <li>Adjusting plans on-the-fly in response to audience dynamics and unscripted moments.</li>
                  <li>Integrating audience feedback in real-time to guide the show’s direction.</li>
                  <li>Managing schedules and coordinating with participants, akin to event planning.</li>
                  <li>Maintaining the show's tone and style to align with overarching goals.</li>
                  <li>Leading the production team effectively in dynamic, less controlled environments.</li>
                </ul>
              </div>
            </div>
          </div>

          <div v-if="form.errors.show_runner_creator_id" v-text="form.errors.show_runner_creator_id"
               class="text-xs text-red-600 mt-1"></div>
        </div>

        <div class="mb-6">
          <label class="block mb-2 uppercase font-bold dark:text-gray-200"
                 for="name"
          >
            Show Name <span class="text-red-500">* REQUIRED</span>
          </label>

          <input v-model="form.name"
                 class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500"
                 type="text"
                 name="name"
                 id="name"
                 required
                 placeholder="Show Name"
          >
          <div v-if="form.errors.name" v-text="form.errors.name" class="text-xs text-red-600 mt-1"></div>
        </div>

        <div class="mb-6">
          <label class="block mb-2 uppercase font-bold dark:text-gray-200"
                 for="category"
          >
            Category <span class="text-red-500">* REQUIRED</span>
          </label>


          <select
              class="border border-gray-400 text-gray-800 p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs "
              v-model="selectedCategoryId" @change="chooseCategory"
          >
            <option disabled :value="null">Choose a category...</option>
            <option v-for="category in categories"
                    :key="category.id" :value="category.id">{{ category.name }}
            </option>

          </select>
          <div v-if="form.errors.category" v-text="form.errors.category"
               class="text-xs text-red-600 mt-1"></div>

          <span class="">{{ showStore.category_description }}</span>
        </div>

        <div class="mb-6">
          <label class="block mb-1 uppercase font-bold dark:text-gray-200"
                 for="sub_category"
          >
            Sub-category
          </label>

          <select
              class="border border-gray-400 text-gray-800 disabled:bg-gray-300 dark:disabled:bg-gray-600 disabled:cursor-not-allowed p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs"
              v-model="selectedSubCategoryId" @change="chooseSubCategory"
          >
            <option v-if="!selectedCategoryId" disabled :value="null">Choose a category first</option>
            <option v-else disabled :value="null">Choose a subcategory...</option>
            <option v-for="subCategory in subCategories" :key="subCategory.id" :value="subCategory.id">
              {{ subCategory?.name }}
            </option>
          </select>
          <span class="">{{ showStore.sub_category_description }}</span>
          <div v-if="form.errors.sub_category" v-text="form.errors.sub_category"
               class="text-xs text-red-600 mt-1"></div>
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

        <SocialMediaLinksStoreUpdateForForm v-model:form="form" />

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
import { computed, onMounted, ref, watch } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
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

let selectedCategoryId = ref(null)
let selectedSubCategoryId = ref(null)

const subCategories = computed(() => {
  const category = props.categories.find(cat => cat.id === selectedCategoryId.value)
  return category ? category.sub_categories : []
})

// Watchers to update the store based on category and subcategory selections
watch(selectedCategoryId, () => {
  showStore.initializeDescriptions(selectedCategoryId.value, selectedSubCategoryId.value)
}, {immediate: true})

watch(selectedSubCategoryId, () => {
  showStore.updateSubCategoryDescription(selectedSubCategoryId.value)
})

onMounted(() => {
  selectedShowRunnerCreatorId.value = defaultShowRunnerId.value
  showStore.categories = props.categories
  showStore.initializeDescriptions(selectedCategoryId.value, selectedSubCategoryId.value)
  selectedTeamId.value = defaultTeamId.value
})

const defaultTeamId = computed(() => {
  return teamStore.id || (props.teams.length > 0 ? props.teams[0].id : null)
})

const defaultShowRunnerId = computed(() => {
  return props.creatorId
})

// Reactive property for the selected team ID
const selectedTeamId = ref(null)

// Reactive property for the selected show_runner ID
const selectedShowRunnerCreatorId = ref(null)

// Watcher to update the teamStore.id when selectedTeamId changes
watch(selectedTeamId, (newId) => {
  teamStore.id = newId
})

const chooseCategory = () => {
  // Update the selected category ID based on the new selection
  // Vue automatically updates selectedCategoryId due to v-model binding
  // So, there is no need to manually set it here

  // Call the store method to update descriptions and subcategories
  showStore.initializeDescriptions(selectedCategoryId.value, selectedSubCategoryId.value)
}

const chooseSubCategory = () => {
  // Update the store state based on the new subcategory selection
  showStore.updateSubCategoryDescription(selectedSubCategoryId.value)
}

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

const showRunnerInfoVisible = ref(false)

const toggleShowRunnerInfo = () => {
  showRunnerInfoVisible.value = !showRunnerInfoVisible.value
}

// Use watch to react to changes in defaultTeamId computed property
watch(defaultTeamId, fetchTeamMembers, {immediate: true}) // immediate: true to run on mount

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

let showCategoryDescription = ref(null)

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
  form.category = showStore.category_id
  form.sub_category = showStore.sub_category_id
  form.team_id = selectedTeamId
  form.show_runner_creator_id = selectedShowRunnerCreatorId
  form.post('/shows')
}

// function chooseCategory(event) {
//   showCategoryDescription = props.categories[event.target.selectedIndex].description
// }

function reset() {
  form.reset()
}

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
