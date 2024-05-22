<template>
  <Head title="Invite Code Report"/>

  <div class="place-self-center flex flex-col">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <div class="flex flex-row justify-end">
        <button @click.prevent="appSettingStore.btnRedirect('/invite_codes')"
                class="btn bg-orange-500 hover:bg-orange-700 text-white">Manage Codes
        </button>
      </div>

      <div class="flex flex-col w-full my-8 p-4 gap-8">

        <div class="overflow-x-auto">
          <div class="flex items-center justify-center">
            <div class="w-full lg:w-5/6">
              <h1 class="w-full text-3xl font-semibold mb-6 text-center">Invite Code Report</h1>
              <div class="bg-white shadow-md rounded my-6">
                <table class="min-w-max w-full table-auto">
                  <thead>
                  <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Code</th>
                    <th class="py-3 px-6 text-left">Role</th>
                    <th class="py-3 px-6 text-left">Volume</th>
                    <th class="py-3 px-6 text-left">Uses</th>
                    <th class="py-3 px-6 text-center">Expiry</th>
                    <th class="py-3 px-6 text-right">Distributor</th>
                  </tr>
                  </thead>
                  <tbody class="text-gray-600 text-sm font-light">
                  <tr v-for="code in codes.data" :key="code?.id" class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap">
                      {{ code?.code }}
                    </td>
                    <td class="py-3 px-6 text-left">
                      {{ code?.role }}
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
                    <td class="py-3 px-6 text-right">
                      {{ code?.created_by_name }}
                    </td>
                  </tr>
                  </tbody>
                </table>

              </div>

              <div v-if="codes.meta.last_page > 1" class="flex justify-between items-center mt-4">
                <button
                    class="join-item btn"
                    :disabled="!codes.links.prev"
                    @click="changePage(codes.links.prev)">
                  «
                </button>
                <span>Page {{ codes.meta.current_page }} of {{ codes.meta.last_page }}</span>
                <button
                    class="join-item btn"
                    :disabled="!codes.links.next"
                    @click="changePage(codes.links.next)">
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

usePageSetup('inviteCodes.report')
const appSettingStore = useAppSettingStore()

const props = defineProps({
  codes: Object,
})

const changePage = (url) => {
  if (url) {
    router.visit(url)
  }
}


</script>
