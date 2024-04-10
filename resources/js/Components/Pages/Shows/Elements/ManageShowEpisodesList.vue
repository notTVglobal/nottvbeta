<template>

  <div>
    <button
        v-if="teamStore.can.createEpisode"
        @click="appSettingStore.btnRedirect(`/shows/${show.slug}/episode/create`)"
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
            Episode Name
          </div>
        </div>
      </td>

      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
        Episode Notes
      </td>

      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
        Episode Status
      </td>
      <td>
        <!--edit button goes in this column-->
      </td>
    </tr>
    </thead>

    <tbody class="divide-y divide-gray-200">

    <ShowEpisode v-for="episode in props.episodes.data"
                 :episode="episode"
                 :showSlug="props.show.slug"
                 :showName="props.show.name"
                 :episodeStatuses="props.episodeStatuses"/>

    </tbody>
  </table>

  <!-- Paginator -->
  <Pagination :data="episodes" class=""/>

</template>

<script setup>
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useTeamStore } from "@/Stores/TeamStore"
import ShowEpisode from "@/Components/Pages/Shows/Elements/ManageShowEpisode"
import Pagination from "@/Components/Global/Paginators/Pagination"

const appSettingStore = useAppSettingStore()
const teamStore = useTeamStore()

let props = defineProps({
  show: Object,
  episodes: Object,
  episodeStatuses: Object,
  filters: Object,
  can: Object,
});

// Access flashed messages from Inertia's props
// const { success, error } = page.props;

// Create refs to hold success and error messages
// const successMessage = ref(success);
// const errorMessage = ref(error);

</script>
