<template>
  <div v-if="showStore.episodeIsBeingDeleted !== episode.id" class="bg-gray-50 grid grid-cols-1 md:grid-cols-5 gap-4 p-4 mb-4 rounded-lg shadow">
    <div class="flex items-center text-sm w-16">
      <div v-if="!episode.episodeNumber">{{ episode.id }}</div>
      <div v-else>{{ episode.episodeNumber }}</div>
    </div>
    <div @click="router.visit(`/shows/${showSlug}/episode/${episode.slug}/manage`)" class="hover:cursor-pointer flex items-center space-x-2">
      <SingleImage :image="episode.image" :alt="episode.name" class="rounded-xl min-w-16 w-16 h-16 object-cover" />
      <span class="hover:text-blue-600 font-semibold dark:text-blue-400 dark:hover:text-blue-200">
        {{ episode.name }}
      </span>
    </div>
    <div class="light:text-gray-600 dark:text-gray-100 text-sm flex justify-center items-center">
      <span v-if="!episode.notes" v-show="showStore.noteEdit !== episode.id" class="italic" @click="editNote">Click here to add/edit a note.</span>
      <span v-if="episode.notes" v-show="showStore.noteEdit !== episode.id" :key="componentKey" @click="editNote">{{ episode.notes }}</span>
      <div v-if="showStore.noteEdit === episode.id">
        <EpisodeNoteEdit :episode="episode" @saveNoteProcessing="reloadNote" />
        <div v-if="showStore.saveNoteProcessing">Saving...</div>
      </div>
    </div>
    <div class="text-right">
      <div class="dropdown dropdown-left">
        <ManageShowEpisodeStatuses
            :episodeStatus="episode.episodeStatus"
            :episodeStatusId="episode.episodeStatusId"
            :episodeStatuses="episodeStatuses"
            :episodeId="episode.id"
            :episodeUlid="episode.ulid"
            :episodeName="episode.name"
            :episodeSlug="episode.slug"
            :showSlug="showSlug"
            :showName="showName"
            :scheduledDateTime="episode.scheduledReleaseDateTime"
        />
      </div>
      <div v-if="episode.episodeStatusId === 6">
        Scheduled for: <br />
        {{ scheduledReleaseDateTime }}
      </div>
      <div v-if="episode.episodeStatusId === 7">
        Released on: <br/>
        {{ releaseDateTime }}
      </div>
    </div>
    <div class="flex flex-row justify-end items-center">
      <button
          v-if="teamStore.can.editShow"
          @click="appSettingStore.btnRedirect(`/shows/${showSlug}/episode/${episode.slug}/edit`)"
          class="px-2 py-2 text-white font-semibold bg-blue-500 hover:bg-blue-600 rounded-lg mr-2 w-16 h-10"
      >
        Edit
      </button>
      <button
          v-if="teamStore.can.manageShow && !episode.isPublished"
          @click="deleteShowEpisode"
          class="px-2 py-2 text-white font-semibold bg-red-500 hover:bg-red-600 rounded-lg w-10 h-10"
      >
        <font-awesome-icon icon="fa-trash-can" />
      </button>
    </div>
  </div>
  <div v-else class="w-full text-center p-4">
    <span class="loading loading-infinity loading-lg"></span>
    <span class="ml-3">The episode is being deleted...</span>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { useAppSettingStore } from "@/Stores/AppSettingStore";
import { useTeamStore } from "@/Stores/TeamStore";
import { useShowStore } from "@/Stores/ShowStore";
import { useUserStore } from "@/Stores/UserStore";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import ManageShowEpisodeStatuses from "@/Components/Pages/Shows/Elements/ManageShowEpisodesStatuses";
import EpisodeNoteEdit from "@/Components/Pages/Shows/Elements/ManageEpisodeEditNote";
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue';

const appSettingStore = useAppSettingStore();
const teamStore = useTeamStore();
const showStore = useShowStore();
const userStore = useUserStore();

const props = defineProps({
  episode: Object,
  episodeStatuses: Object,
  showSlug: String,
  showName: String,
});

const showEpisodeStatuses = ref(false);
const releaseDateTime = userStore.formatLongDateTimeFromUtcToUserTimezone(props.episode.releaseDateTime);
const scheduledReleaseDateTime = userStore.formatDateInUserTimezone(props.episode.scheduledReleaseDateTime, 'ddd DD MMM YYYY, hh:mm A' + ' ' + userStore.timezoneAbbreviation);

showStore.noteEdit = 0;
const componentKey = ref(0);

function reloadNote() {
  props.episode.notes = showStore.notes;
  componentKey.value += 1;
}

let form = useForm({
  note: '',
});

function editNote() {
  showStore.noteEdit = props.episode.id;
}

function openEpisodeStatuses() {
  document.getElementById('episodeStatuses').showModal();
}

const deleteShowEpisode = async () => {
  const confirmation = confirm('Are you sure you want to delete this show episode?');

  if (confirmation) {
    try {
      showStore.episodeIsBeingDeleted = props.episode.id;
      await axios.delete(`/shows/${props.showSlug}/episode/${props.episode.slug}`)
          .then((response) => {
            if (response.status === 200) {
              showStore.errorMessage = response.data.message;
              router.reload();
              showStore.episodeIsBeingDeleted = 0;
            } else {
              showStore.errorMessage = response.statusText;
              console.error('Delete request failed:', response.statusText);
              showStore.episodeIsBeingDeleted = 0;
              router.reload();
            }
          })
          .catch((error) => {
            console.error('Error deleting show episode:', error);
            showStore.errorMessage = error;
            showStore.episodeIsBeingDeleted = 0;
            router.reload();
          });
    } catch (error) {
      console.error('Error deleting show episode:', error);
    }
  }
};
</script>

<style scoped>
.grid {
  display: grid;
  grid-template-columns: 1fr;
}

@media (min-width: 768px) {
  .grid {
    grid-template-columns: 4rem 1fr 1fr 1fr 1fr;
  }
}

.min-w-16 {
  min-width: 4rem;
}

.max-w-16 {
  max-width: 4rem;
}

.max-h-16 {
  max-height: 4rem;
}
</style>
