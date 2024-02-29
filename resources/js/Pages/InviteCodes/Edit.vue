<template>

  <Head title="Edit Invite Code"/>

  <div class="place-self-center flex flex-col">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <div class="flex flex-row justify-end">
        <button @click.prevent="appSettingStore.btnRedirect('/invite_codes')" class="btn bg-orange-500 hover:bg-orange-700 text-white">List all codes</button>
      </div>

      <div class="flex flex-col w-96 mx-auto my-8 p-4 gap-8">
        <h1 class="w-full text-center">Edit Invite Code</h1>
        <form>
          <div class="flex flex-col space-y-4">
            <div class="flex flex-col w-full">
              <div class="mb-2 flex flex-row justify-between"><label for="code" class="text-xs font-semibold text-gray-800 uppercase">Code </label>
                <button @click.prevent="generateRandomCode" class="text-blue-700 hover:text-blue-500 underline hover:cursor-pointer">generate new random code</button></div>
              <input v-model="form.code" id="code" type="text" required class="input input-bordered w-full" >
            </div>
            <div v-if="formErrors['code']" class="mb-4 mt-2 text-red-700">{{ formErrors['code'][0] }}</div>


            <div class="flex flex-col w-full dropdown">
              <label for="userSearch" class="mb-2 text-xs font-semibold text-gray-800 uppercase">Assign Distributor</label>
              <input type="text" id="userSearch" v-model="searchQuery" @input="searchUsers" placeholder="Search users..." class="input input-bordered w-full">
            </div>
            <div v-if="searchQuery.length > 1 && searchResults.length" class="flex flex-col">
              <ul class="p-2 shadow menu dropdown-content z-10 bg-base-200 rounded-box w-full max-h-60 overflow-y-auto overflow-x-hidden">
                <li v-for="user in searchResults" :key="user.id">
                  <a @click.prevent="assignUser(user)">
                    {{ user.name }} - {{ user.email }}
                  </a>
                </li>
              </ul>
            </div>
            <div v-if="formErrors['created_by_user_id']" class="mb-4 mt-2 text-red-700">{{ formErrors['created_by_user_id'][0] }}</div>




            <div class="flex flex-col w-full">
              <label for="role" class="mb-2 text-xs font-semibold text-gray-800 uppercase">Role</label>
              <select v-model="form.user_role_id" id="role" class="select select-bordered w-full text-gray-900">
                <option v-for="role in roles" :value="role.id" :key="role.id" class="text-gray-900">{{ role.role }}</option>
              </select>
            </div>
            <div v-if="formErrors['user_role_id']" class="mb-4 mt-2 text-red-700">{{ formErrors['user_role_id'][0] }}</div>


            <div class="flex flex-col w-full">
              <label for="volume" class="mb-2 text-xs font-semibold text-gray-800 uppercase">Volume</label>
              <input v-model="form.volume" id="volume" type="number" min="1" required class="input input-bordered w-full" >
            </div>
            <div v-if="formErrors['volume']" class="mb-4 mt-2 text-red-700">{{ formErrors['volume'][0] }}</div>


            <div class="flex flex-col w-full">
              <label for="expiry_date" class="mb-2 text-xs font-semibold text-gray-800 uppercase">Expiry Date (optional)</label>
              <input v-model="form.expiry_date" id="expiry_date" type="date">
            </div>
            <div v-if="formErrors['expiry_date']" class="mb-4 mt-2 text-red-700">{{ formErrors['expiry_date'][0] }}</div>


            <button @click.prevent="updateCode" class="btn btn-primary text-white">Update Code</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, reactive, ref, watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { throttle } from 'lodash';
import Message from '@/Components/Global/Modals/Messages'

usePageSetup('inviteCodes.edit')
const appSettingStore = useAppSettingStore()

const props = defineProps({
  inviteCode: Object,
  roles: Object,
})

const form = reactive({
  code: props.inviteCode.code,
  created_by_user_id: props.inviteCode.created_by_id,
  user_role_id: props.inviteCode.role_id,
  volume: props.inviteCode.volume,
  expiry_date: props.inviteCode.expiry_date,
})

let formErrors = reactive({});

const searchQuery = ref(props.inviteCode.created_by_name || '');
const searchResults = ref([]);
const isSelectingUser = ref(false); // Flag to indicate user selection is in progress

const selectedUserDetails = ref({
  name: props.inviteCode.created_by_name || '',
  email: '',
});

// Function to clear search
const clearSearch = () => {
  if (form.created_by_user_id && selectedUserDetails.value.name) {
    // If a user is selected, display the user's name in the searchQuery input
    searchQuery.value = selectedUserDetails.value.name;
    searchResults.value = '';
  } else {
    // Clear searchQuery if no user is selected
    searchQuery.value = '';
    searchResults.value = '';
  }
  // Optionally clear search results here too
};

// Function to handle keydown events
const handleKeydown = (event) => {
  if (event.key === 'Escape') {
    clearSearch();
  }
};

// Add event listener when component is mounted
onMounted(() => {
  window.addEventListener('keydown', handleKeydown);
});

// Remove event listener when component is unmounted
onUnmounted(() => {
  window.removeEventListener('keydown', handleKeydown);
});

watch(searchQuery, throttle(async (value) => {
  if (isSelectingUser.value || !value.trim()) {
    searchResults.value = [];
    return;
  }

  try {
    const { data } = await axios.get(`/admin/users/search?query=${encodeURIComponent(value)}`);
    searchResults.value = data;
  } catch (error) {
    console.error('Error fetching search results:', error);
    searchResults.value = [];
  }
}, 300));

const assignUser = (user) => {
  // Indicate that a user selection is in progress
  isSelectingUser.value = true;
  // Update form data with the selected user's ID
  form.created_by_user_id = user.id;
  selectedUserDetails.value.name = user.name;
  selectedUserDetails.value.email = user.email;
  // Update the search input with the user's name
  searchQuery.value = user.name;

  // Clear search results after assignment
  searchResults.value = [];

  // Reset the flag after a slight delay to ensure it's not immediately reset
  // This delay allows any pending asynchronous updates to complete
  setTimeout(() => {
    isSelectingUser.value = false;
  }, 100);
};


const generateRandomCode = () => {
  axios.get('/generate-invite-code').then(response => {
    // Assuming the response contains the new code
    form.code = response.data.suggestedCode;
  }).catch(error => {
    console.error('There was an error generating the invite code:', error);
  });
}

const updateCode = () => {
  console.log("Form data being submitted:", form);
  axios.put(`/invite_codes/${props.inviteCode.id}`, form)
      .then(response => {
        // Handle success
        Object.keys(form).forEach(key => form[key] = null);
        formErrors = reactive({}); // Or formErrors.value = {} if formErrors is a ref
        Inertia.visit('/invite_codes');
      })
      .catch(error => {
        if (error.response && error.response.data && error.response.data.errors) {
          // Update formErrors with the new errors
          Object.assign(formErrors, error.response.data.errors); // For reactive
          // Or formErrors.value = error.response.data.errors; if formErrors is a ref
        }
      });
};
</script>