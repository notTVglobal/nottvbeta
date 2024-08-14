<template>
  <div v-if="can.manageTeam">
    <button
        class="bg-green-500 hover:bg-green-600 text-white font-semibold ml-2 my-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
        @click.prevent="openModal"
        :disabled="!teamStore.spotsRemaining"
    >Add Member ({{ teamStore.spotsRemaining }} spots left)
    </button>
  </div>
  <div v-show="!isLoading" class="overflow-x-auto">
    <table class="table min-w-full">
      <thead>
      <tr>
        <td class="px-6 min-w-[3rem] max-w-[3rem] whitespace-nowrap text-sm font-medium">
          <!-- Avatar -->
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
          <div class="flex items-center">

            <div class="pl-3 text-sm font-medium">
              Name
            </div>

          </div>
        </td>

        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
          Position
        </td>

        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
          Phone
        </td>

        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
          Email
        </td>

        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
          Status
        </td>

        <td v-if="can.manageTeam" class="px-6 py-4 whitespace-nowrap text-sm font-medium">
          <!-- Remove -->
        </td>
      </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
      <TeamMember v-for="member in members" :member="member" :can="can"/>
      </tbody>
    </table>
    <!-- Paginator -->
<!--    <Pagination v-if="teamStore.team.members?.links" :data="paginatedData" @page-change="handlePageChange" class="mt-6"/>-->
    <!-- Custom Paginator -->
    <CustomPagination v-if="paginatedData.links"
                      :links="paginatedData.links"
                      :current_page="paginatedData.current_page"
                      :last_page="paginatedData.last_page"
                      @page-change="handlePageChange" />

    <!-- Temporary Paginator -->
    <div class="flex justify-between items-center mt-6">
<!--      <button-->
<!--          :disabled="currentPage === 1"-->
<!--          @click="handlePageChange(currentPage - 1)"-->
<!--          class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded"-->
<!--      >-->
<!--        Previous-->
<!--      </button>-->
<!--      <span>Page {{ currentPage }} of {{ paginatedData.value.last_page }}</span>-->
<!--      <button-->
<!--          :disabled="currentPage === paginatedData.value.last_page"-->
<!--          @click="handlePageChange(currentPage + 1)"-->
<!--          class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded"-->
<!--      >-->
<!--        Next-->
<!--      </button>-->
    </div>



  </div>

  <div v-show="isLoading">
    <p><span class="loading"/> Loading...</p>
  </div>


  <div class="text-right px-3 mt-2 text-gray-600 italic w-full"
       v-show="!teamStore.spotsRemaining">
    There are no remaining team spots. Edit the team to add more.
  </div>

  <Teleport to="body">
    <TeamAddMember v-if="teamStore.showModal" @close="teamStore.showModal = false"/>
  </Teleport>


</template>

<script setup>
import { computed, onBeforeMount, onMounted, ref } from 'vue'
import { useTeamStore } from '@/Stores/TeamStore'
import TeamMember from '@/Components/Pages/Teams/Manage/Elements/TeamMember'
import TeamAddMember from '@/Components/Pages/Teams/Manage/Elements/TeamAddMember'
import Pagination from '@/Components/Global/Paginators/Pagination.vue'
import CustomPagination from '@/Components/Global/Paginators/CustomPagination.vue'

const teamStore = useTeamStore()

// Local state for pagination
// const currentPage = ref(1)
const isLoading = ref(false)


// Map store state to local computed properties
const members = computed(() => teamStore.team.members.data)
const paginatedData = computed(() => ({
  current_page: teamStore.team.members?.current_page || 1,
  last_page: teamStore.team.members?.last_page || 1,
  links: teamStore.team.members?.links || {}, // Provide an empty object as the default
}))

// Map store state to local computed properties
const can = computed(() => teamStore.can || {})

// Fetch members when the component mounts or page changes
async function fetchMembers(page = 1) {
  isLoading.value = true;  // Set isLoading to true when the fetch starts
  try {
    await teamStore.fetchPaginatedTeamMembers(page);

    // After fetching, check if the current page is valid
    const totalItems = teamStore.team.members.total;
    const itemsPerPage = teamStore.team.members.per_page;
    const lastPage = Math.ceil(totalItems / itemsPerPage);

    // If the current page is greater than the last available page, fetch the last page
    if (page > lastPage) {
      await fetchMembers(lastPage);  // Fetch the last valid page
    }

    console.log('Fetched members for page:', page);
  } catch (error) {
    console.error('Error fetching members:', error);
  } finally {
    isLoading.value = false;  // Set isLoading to false when the fetch is complete
  }
}

function handlePageChange(page) {
  fetchMembers(page)
}

function openModal() {
  teamStore.showModal = true
}

onBeforeMount(async () => {
  teamStore.showModal = false
})

// Fetch members on component mount
onMounted(() => {
  fetchMembers(1) // Load the first page by default
})

</script>
