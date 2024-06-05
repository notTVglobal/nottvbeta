<template>
  <Head title="Creators"/>

  <div class="container mx-auto py-12 px-6">
    <div id="topDiv" class="bg-white text-black p-6 rounded-lg shadow-lg">
      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <div class="flex justify-between items-center mb-6">
        <h1 class="text-4xl font-bold text-gray-800">Creators</h1>
        <input v-model="search" type="search" placeholder="Search..." class="border px-4 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"/>
      </div>

      <div class="overflow-hidden border border-gray-200 rounded-lg shadow-md">
        <table class="min-w-full bg-white">
          <tbody class="divide-y divide-gray-200">
          <tr v-for="creator in filteredCreators" :key="creator.id">
            <td class="px-6 py-4 whitespace-nowrap hover:bg-gray-100">
              <div v-if="creator.profile_is_public" class="flex items-center">
                <Link :href="`/creators/${creator.slug}`" class="flex items-center w-full h-full">
                  <div class="flex-shrink-0 h-12 w-12">
                    <img v-if="creator.profile_photo_path"
                         :src="'/storage/' + creator.profile_photo_path"
                         class="h-12 w-12 rounded-full object-cover">
                    <img v-else
                         :src="creator.profile_photo_url"
                         class="h-12 w-12 rounded-full object-cover bg-gray-300">
                  </div>
                  <div class="ml-4">
                      <span class="text-lg font-semibold text-indigo-600 hover:text-indigo-900">
                        {{ creator.name }}
                      </span>
                  </div>
                </Link>
              </div>
              <div v-else class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12">
                  <img v-if="creator.profile_photo_path"
                       :src="'/storage/' + creator.profile_photo_path"
                       class="h-12 w-12 rounded-full object-cover">
                  <img v-else
                       :src="creator.profile_photo_url"
                       class="h-12 w-12 rounded-full object-cover bg-gray-300">
                </div>
                <div class="ml-4">
                    <span class="text-lg font-semibold text-gray-600">
                      {{ creator.name }}
                    </span>
                </div>
              </div>
            </td>
          </tr>
          </tbody>
        </table>

        <!-- Paginator -->
        <Pagination :data="creators" class="mt-6"/>
      </div>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'
import throttle from 'lodash/throttle'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import Pagination from '@/Components/Global/Paginators/Pagination'
import Message from '@/Components/Global/Modals/Messages'

usePageSetup('creators')

const appSettingStore = useAppSettingStore()

const props = defineProps({
  creators: Object,
  filters: Object,
  auth: Object, // assuming auth is passed as a prop containing user information
})

const search = ref(props.filters.search)

const isAdmin = computed(() => {
  return props.auth.user?.isAdmin || false
})

const filteredCreators = computed(() => {
  return props.creators.data.filter(creator => {
    return creator.profile_is_public || isAdmin.value
  })
})

watch(search, throttle(function (value) {
  router.get('/creators', { search: value }, {
    preserveState: true,
    replace: true,
  })
}, 300))
</script>
