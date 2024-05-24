<template>

  <div>
    <button
        v-if="teamStore.can.createEpisode"
        @click="appSettingStore.btnRedirect(`/shows/${showStore.show.slug}/episode/create`)"
        class="ml-6 my-4 px-4 py-2 text-white font-semibold bg-green-500 hover:bg-green-600 rounded-lg disabled:bg-gray-400"

    >Create Episode
    </button>
  </div>
  <table class="min-w-full divide-y divide-gray-200">
    <thead class="divide-y divide-gray-200">
    <!--                                <tr v-for="episode in episodes.data" :key="episode.id">-->
    <tr>
      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
        Ep.#
      </td>
      <td class="px-6 py-4 whitespace-nowrap w-1/3">
        <div class="flex items-start">
          <div class="text-sm font-medium">
            <!--                                                    <Link :href="`/admin/users/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">{{ episode.name }}</Link>-->
            Name
          </div>
        </div>
      </td>

      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
        Notes
      </td>

      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
        Status
      </td>

      <td>
        <!--edit button goes in this column-->
      </td>
    </tr>
    </thead>

    <tbody class="divide-y divide-gray-200">

    <ManageShowEpisode v-for="episode in showStore.episodes.data"
                 :episode="episode"
                 :showSlug="showStore.show.slug"
                 :showName="showStore.show.name"
                 :episodeStatuses="showStore.episodeStatuses"/>

    </tbody>
  </table>

  <!-- Paginator -->
  <Pagination :data="showStore.episodes" class=""/>

</template>

<script setup>
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useTeamStore } from "@/Stores/TeamStore"
import { useShowStore } from "@/Stores/ShowStore"
import ManageShowEpisode from "@/Components/Pages/Shows/Elements/ManageShowEpisode"
import Pagination from "@/Components/Global/Paginators/Pagination"

const appSettingStore = useAppSettingStore()
const teamStore = useTeamStore()
const showStore = useShowStore()

// Access flashed messages from Inertia's props
// const { success, error } = page.props;

// Create refs to hold success and error messages
// const successMessage = ref(success);
// const errorMessage = ref(error);

</script>
