<template>
  <div>
    <button
        v-if="teamStore.can.createEpisode"
        @click="appSettingStore.btnRedirect(`/showEpisodes/${showStore.show.slug}/episode/create`)"
        class="ml-6 my-4 px-4 py-2 text-white font-semibold bg-green-500 hover:bg-green-600 rounded-lg disabled:bg-gray-400"
    >
      Create Episode
    </button>
  </div>
  <div class="overflow-x-auto">
    <div class="min-w-full divide-y divide-gray-200 grid grid-cols-1 md:grid-cols-5">
      <div class="font-medium p-4 text-left w-16">Ep.#</div>
      <div class="font-medium p-4 text-left"></div>
      <div class="font-medium p-4 text-center"></div>
      <div class="font-medium p-4 text-right"></div>
      <div class="font-medium p-4 text-right"></div>
    </div>
    <div class="divide-y divide-gray-200">
      <ManageShowEpisode
          v-for="episode in showStore.episodes.data"
          :key="episode.id"
          :episode="episode"
          :showSlug="showStore.show.slug"
          :showName="showStore.show.name"
          :episodeStatuses="showStore.episodeStatuses"
      />
    </div>
  </div>
  <!-- Paginator -->
  <Pagination :data="showStore.episodes" />
</template>

<script setup>
import { useAppSettingStore } from "@/Stores/AppSettingStore";
import { useTeamStore } from "@/Stores/TeamStore";
import { useShowStore } from "@/Stores/ShowStore";
import ManageShowEpisode from "@/Components/Pages/Shows/Elements/ManageShowEpisode";
import Pagination from "@/Components/Global/Paginators/Pagination";

const appSettingStore = useAppSettingStore();
const teamStore = useTeamStore();
const showStore = useShowStore();
</script>
