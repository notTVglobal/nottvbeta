<template>
  <Head title="Invite Codes"/>

  <div class="place-self-center flex flex-col">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <div class="flex flex-row flex-wrap justify-between">
        <div>
          <form>
            <div class="mb-6">
              <label class="block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-300"
                     for="code"
              >
                NEW CODE
              </label>

              <input v-model="form.code"
                     class="border border-gray-400 p-2 w-64 rounded-lg text-black"
                     type="text"
                     name="code"
                     id="code"
              >
              <div v-if="form.errors.code" v-text="form.errors.code"
                   class="text-xs text-red-600 mt-1"></div>
            </div>

            <div class="flex justify-start my-6 mr-6">
              <button
                  @click.prevent="submit"
                  class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded disabled:bg-gray-400"
                  :disabled="form.processing"
              >
                Add Code
              </button>


              <!--                        <JetValidationErrors class="ml-4" :key="props.messageKey"/>-->
            </div>

          </form>
        </div>


        <div class="flex flex-col justify-start">
          <div class="flex flex-wrap">
            <div class="flex-1 min-w-0">
              <div class="flex flex-row flex-wrap">
                <button
                    @click.prevent="exportCodes"
                    class="mr-2 btn bg-blue-500 hover:bg-blue-700 text-white"
                    disabled
                >
                  Export Codes
                </button>
                <button @click.prevent="claimAllCodes"
                        class="mr-2 btn bg-orange-500 hover:bg-orange-700 text-white">Claim All Codes
                </button>
                <button @click.prevent="appSettingStore.btnRedirect('/invite_codes/report')"
                        class="mr-2 btn bg-blue-500 hover:bg-blue-700 text-white">Report
                </button>
                <button @click.prevent="appSettingStore.btnRedirect('/invite_codes/create')"
                        class="btn bg-green-500 hover:bg-green-700 text-white">Create Code
                </button>
              </div>
              <div class="flex flex-row gap-3 pt-3 shrink">
                <div class="flex flex-col">
                  <div class="w-38 pb-2">
                    How many <span class="font-semibold">Creator Codes</span><br/>
                    do new creators start with?
                  </div>
                  <div>
                    <input type="number" v-model.number="inviteCodeSettings.creatorCodes" placeholder="Number of creator codes"
                           class="input bg-white text-black dark:bg-gray-800 dark:text-white input-bordered input-primary w-28 max-w-xs"/>
                    <button @click="updateInviteCodeSettings('creatorCodes', inviteCodeSettings.creatorCodes)" class="btn btn-primary ml-2">Save</button>
                    <div v-if="saveStatus" class="text-sm mt-2">{{ saveStatus }}</div>
                  </div>
                </div>
                <div class="flex flex-col">
                  <div class="w-38 pb-2">
                    How many <span class="font-semibold">Viewer Codes</span><br/>
                    do new creators start with?
                  </div>
                  <div>
                    <input type="number" v-model.number="inviteCodeSettings.viewerCodes" placeholder="Number of viewer codes"
                           class="input bg-white text-black dark:bg-gray-800 dark:text-white input-bordered input-primary w-28 max-w-xs"/>
                    <button @click="updateInviteCodeSettings('viewerCodes', inviteCodeSettings.viewerCodes)" class="btn btn-primary ml-2">Save</button>
                    <div v-if="saveStatus" class="text-sm mt-2">{{ saveStatus }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


      <div class="flex flex-col w-full my-8 p-4 gap-8">

        <div class="overflow-x-auto">
          <div class="flex items-center justify-center">
            <div class="w-full lg:w-5/6">
              <div class="flex justify-between mt-4">
                <h1 class="text-xl font-semibold">Manage Invite Codes</h1>
                <div class="flex flex-row justify-end gap-x-4">
                  <input v-model="search" type="search" placeholder="Search..."
                         class="text-black border px-2 rounded-lg"/>
                </div>
              </div>

              <div class="bg-white shadow-md rounded my-6">
                <table class="min-w-max w-full table-auto">
                  <thead>
                  <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Code</th>
                    <th class="py-3 px-6 text-left">Role</th>
                    <th class="py-3 px-6 text-left">Distributor</th>
                    <th class="py-3 px-6 text-left">Volume</th>
                    <th class="py-3 px-6 text-left">Uses</th>
                    <th class="py-3 px-6 text-center">Expiry</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                  </tr>
                  </thead>
                  <tbody class="text-gray-600 text-sm font-light">
                  <tr v-for="code in inviteCodes.data" :key="code?.id"
                      class="border-b border-gray-200 hover:bg-gray-100" :class="[{ 'bg-gray-100': code.claimed }]">
                    <td class="py-3 px-6 text-left whitespace-nowrap">
                      {{ code?.code }}
                    </td>
                    <td class="py-3 px-6 text-left">
                      {{ code?.role }}
                    </td>
                    <td class="py-3 px-6 text-left">
                      {{ code?.created_by_name }}
                    </td>
                    <td class="py-3 px-6 text-left">
                      {{ code?.volume }}
                    </td>
                    <td class="py-3 px-6 text-left">
                      {{ code?.uses }}
                    </td>
                    <td class="py-3 px-6 text-center">
                      <div v-if="!code.claimed">
                        <span v-if="code?.expiry_date">{{ formatDate(code?.expiry_date) }}</span>
                        <span v-else class="text-xs text-gray-600">no expiry</span>
                      </div>
                      <div v-else class="text-primary"> Code is claimed</div>
                    </td>
                    <td class="py-3 px-6 text-center">
                      <div v-if="!code.claimed" class="flex item-center justify-center space-x-4">
                        <!-- Example action buttons -->
                        <div class="w-4 mr-4 transform hover:text-blue-500 hover:scale-110">
                          <button @click.prevent="claimCode(code.id)">Claim</button>
                        </div>
                        <div class="w-4 transform hover:text-purple-500 hover:scale-110">
                          <Link :href="`/invite_codes/${code.ulid}/edit`">Edit</Link>
                        </div>
                        <div class="w-4 transform hover:text-red-500 hover:scale-110">
                          <button @click.prevent="deleteCode(code?.ulid)">Delete</button>
                        </div>
                      </div>

                    </td>
                  </tr>
                  </tbody>
                </table>

              </div>

              <div v-if="inviteCodes.meta.last_page > 1" class="flex justify-between items-center mt-4">
                <button
                    class="join-item btn"
                    :disabled="!inviteCodes.links.prev"
                    @click="changePage(inviteCodes.links.prev)">
                  «
                </button>
                <span>Page {{ inviteCodes.meta.current_page }} of {{ inviteCodes.meta.last_page }}</span>
                <button
                    class="join-item btn"
                    :disabled="!inviteCodes.links.next"
                    @click="changePage(inviteCodes.links.next)">
                  »
                </button>
              </div>

            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import Message from '@/Components/Global/Modals/Messages'
import { onMounted, ref, watch } from 'vue'
import throttle from 'lodash/throttle'
import { useForm } from '@inertiajs/vue3'

usePageSetup('inviteCodes')
const appSettingStore = useAppSettingStore()

const props = defineProps({
  inviteCodes: Object,
  filters: null,
})

const inviteCodeSettings = ref({ viewerCodes: 0, creatorCodes: 0 })
const saveStatus = ref('');


// Function to fetch settings
async function fetchInviteCodeSettings() {
  try {
    const response = await axios.get('/invite-code-settings')
    inviteCodeSettings.value = typeof response.data === 'string' ? JSON.parse(response.data) : response.data;
  } catch (error) {
    console.error('Failed to fetch invite code settings:', error)
  }
}

// Function to update invite code settings
const updateInviteCodeSettings = async (key, value) => {
  try {
    const updatedSettings = { ...inviteCodeSettings.value, [key]: value }
    await axios.patch('/invite-code-settings', updatedSettings)
    saveStatus.value = 'Settings saved successfully!';
    // Optionally, clear the message after a few seconds
    setTimeout(() => saveStatus.value = '', 3000);

    // await fetchInviteCodeSettings()
  } catch (error) {
    console.error('Failed to save settings:', error);
    saveStatus.value = 'Failed to save settings.';
    // Optionally, clear the message after a few seconds
    setTimeout(() => saveStatus.value = '', 3000);
  }
}

// // Function to update viewer code settings
// async function updateViewerCodes() {
//   try {
//     await axios.patch('/invite-code-settings', {viewerCodes: viewerCodes.value})
//     // Handle success
//   } catch (error) {
//     console.error('Failed to update viewer code settings:', error)
//   }
// }

// Fetch settings when component mounts
onMounted(fetchInviteCodeSettings)

let submit = () => {
  form.post('/invite_codes/quick-add')
  form.code = ''
}

let exportCodes = () => {
  router.visit(route('inviteCodes.export'))
}

let form = useForm({
  code: '',
})

let search = ref(props.filters.search)

watch(search, throttle(function (value) {
  router.get('/invite_codes', {search: value}, {
    preserveState: true,
    replace: true,
  })
}, 300))

const changePage = (url) => {
  if (url) {
    router.visit(url)
  }
}

const deleteCode = (id) => {
  if (!confirm('Are you sure you want to delete this invite code?')) {
    return
  }

  router.delete(`/invite_codes/${id}`, {
    onSuccess: () => {
      // Optionally refresh the page or modify the local state to reflect the deletion
    },
  })
}

const claimCode = (codeId) => {
  if (!confirm('Are you sure you want to claim this invite code?')) {
    return
  }

  router.post(`/invite_codes/claim/${codeId}`, {
    onSuccess: () => {
      // Optionally refresh the page or modify the local state to reflect the deletion
    },
  })
}

const claimAllCodes = () => {
  if (!confirm('Are you sure you want to claim all of the invite codes?')) {
    return
  }

  router.post('/invite_codes/claim_all', {
    onSuccess: () => {
      // Optionally refresh the page or modify the local state to reflect the deletion
    },
  })
}
</script>
