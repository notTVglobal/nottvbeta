<template>
  <Head title="Invite Codes"/>

  <div class="place-self-center flex flex-col">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <div class="flex flex-row justify-end">
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

      <div class="flex flex-col w-full my-8 p-4 gap-8">

        <div class="overflow-x-auto">
          <div class="flex items-center justify-center">
            <div class="w-full lg:w-5/6">
              <h1 class="text-xl font-semibold mb-6">Manage Invite Codes</h1>
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
                  <tr v-for="code in inviteCodes.data" :key="code?.id" class="border-b border-gray-200 hover:bg-gray-100" :class="[{ 'bg-gray-100': code.claimed }]">
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
                      <div v-else class="text-primary"> Code is claimed </div>
                    </td>
                    <td class="py-3 px-6 text-center">
                      <div v-if="!code.claimed" class="flex item-center justify-center space-x-4">
                        <!-- Example action buttons -->
                        <div class="w-4 mr-4 transform hover:text-blue-500 hover:scale-110">
                          <button @click.prevent="claimCode(code.id)">Claim</button>
                        </div>
                        <div class="w-4 transform hover:text-purple-500 hover:scale-110">
                          <Link :href="`/invite_codes/${code.id}/edit`">Edit</Link>
                        </div>
                        <div class="w-4 transform hover:text-red-500 hover:scale-110">
                          <button @click.prevent="deleteCode(code?.id)">Delete</button>
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
import { Inertia } from '@inertiajs/inertia'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import Message from '@/Components/Global/Modals/Messages'

usePageSetup('inviteCodes')
const appSettingStore = useAppSettingStore()

const props = defineProps({
  inviteCodes: Object,
})

const changePage = (url) => {
  if (url) {
    Inertia.visit(url)
  }
}

const deleteCode = (id) => {
  if (!confirm('Are you sure you want to delete this invite code?')) {
    return;
  }

  Inertia.delete(`/invite_codes/${id}`, {
    onSuccess: () => {
      // Optionally refresh the page or modify the local state to reflect the deletion
    },
  });
};

const claimCode = (codeId) => {
  if (!confirm('Are you sure you want to claim all of the invite codes?')) {
    return;
  }

  Inertia.post(`/invite_codes/claim/${codeId}`, {
    onSuccess: () => {
      // Optionally refresh the page or modify the local state to reflect the deletion
    },
  });
}

const claimAllCodes = () => {
  if (!confirm('Are you sure you want to claim all of the invite codes?')) {
    return;
  }

  Inertia.post('/invite_codes/claim_all', {
    onSuccess: () => {
      // Optionally refresh the page or modify the local state to reflect the deletion
    },
  });
}
</script>