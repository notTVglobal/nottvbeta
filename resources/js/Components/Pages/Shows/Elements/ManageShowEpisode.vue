<template>
  <tr v-if="showStore.episodeIsBeingDeleted !== episode.id">
    <td class="px-6 py-4 text-sm">

      <!-- If there is no episode number set by the user
             the episode number defaults to the episode id           -->
      <div v-if="!episode.episodeNumber">{{ episode.id }}</div>
      <div v-if="episode.episodeNumber">{{ episode.episodeNumber }}</div>

    </td>
    <td class="text-xl font-medium flex items-center gap-x-4 px-6 py-4 uppercase w-fit">
      <!--            <img :src="`/storage/images/${episode.poster}`" alt="" class="rounded-xl w-10">-->
      <!--                                                    <Link :href="`/admin/users/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">{{ episode.name }}</Link>-->
      <Link :href="`/shows/${showSlug}/episode/${episode.slug}/manage`"
            class="hover:text-blue-600 font-semibold dark:text-blue-400 dark:hover:text-blue-200">

        {{ episode.name }}

      </Link>
    </td>

    <!--        <td class="text-gray-500 px-6 py-4 text-sm">-->
    <!--            {{episode.notes}}-->
    <!--        </td>-->
    <td class="light:text-gray-600 dark:text-gray-100 px-6 py-4 text-sm w-full min-w-[16rem]">
      <span v-if="!episode.notes" v-show="showStore.noteEdit !== props.episode.id" class="italic" @click="editNote">Click here to add/edit a note.</span>
      <span v-if="episode.notes" v-show="showStore.noteEdit !== props.episode.id" :key="componentKey" @click="editNote">{{
          episode.notes
        }}</span>
      <div v-if="showStore.noteEdit === props.episode.id">

        <EpisodeNoteEdit :episode="props.episode" v-on:saveNoteProcessing="reloadNote"/>
        <div v-if="showStore.saveNoteProcessing">Saving...</div>
      </div>

    </td>
    <td class="px-6 py-4 text-right">
      <div class="dropdown dropdown-left">
        <!--                <button tabindex="0" :class="episodeStatus" @click="openEpisodeStatuses">{{ episode.episodeStatus }}</button>-->
        <ShowEpisodeStatuses :episodeStatus="props.episode.episodeStatus"
                             :episodeStatusId="props.episode.episodeStatusId"
                             :episodeStatuses="props.episodeStatuses"
                             :episodeId="props.episode.id"
                             :episodeUlid="props.episode.ulid"
                             :episodeName="props.episode.name"
                             :episodeSlug="props.episode.slug"
                             :showSlug="props.showSlug"
                             :showName="props.showName"
                             :scheduledDateTime="props.episode.scheduledReleaseDateTime"/>
      </div>
      <div v-if="episode.episodeStatusId === 7">
        {{ releaseDateTime }}
      </div>

    </td>
    <td>
      <div class="flex flex-row justify-start mr-2">
        <div>
          <button
              v-if="teamStore.can.editShow"
              @click="appSettingStore.btnRedirect(`/shows/${showSlug}/episode/${episode.slug}/edit`)"
              class="px-4 py-2 text-white font-semibold bg-blue-500 hover:bg-blue-600 rounded-lg mr-2"
          >Edit
          </button>
        </div>
        <button
            v-if="teamStore.can.manageShow && !props.episode.isPublished"
            @click="deleteShowEpisode"
            class="px-4 py-2 text-white font-semibold bg-red-500 hover:bg-red-600 rounded-lg"
        >
          <font-awesome-icon icon="fa-trash-can"/>
        </button>
      </div>
    </td>
  </tr>
  <tr v-else>
    <td class="w-full text-center">
      <span class="loading loading-infinity loading-lg"></span><span class="ml-3">The episode is being deleted...</span>
    </td>
  </tr>
</template>

<script setup>
import { Inertia } from "@inertiajs/inertia"
import { useForm } from "@inertiajs/inertia-vue3"
import { computed, ref } from "vue"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useTeamStore } from "@/Stores/TeamStore"
import { useShowStore } from "@/Stores/ShowStore"
import { useUserStore } from "@/Stores/UserStore"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import ShowEpisodeStatuses from "@/Components/Pages/Shows/Elements/ManageShowEpisodesStatuses"
import EpisodeNoteEdit from "@/Components/Pages/Shows/Elements/ManageEpisodeEditNote"

const appSettingStore = useAppSettingStore()
const teamStore = useTeamStore()
const showStore = useShowStore()
const userStore = useUserStore()

let props = defineProps({
  episode: Object,
  episodeStatuses: Object,
  showSlug: String,
  showName: String,
});

let showEpisodeStatuses = ref(false)

const releaseDateTime = userStore.formatDateInUserTimezone(props.episode.releaseDateTime, 'ddd DD MMM YYYY')

showStore.noteEdit = 0
const componentKey = ref(0);

function reloadNote() {
  props.episode.notes = showStore.note;
  componentKey.value += 1;
}

let form = useForm({
  note: '',
});

function editNote() {
  showStore.noteEdit = props.episode.id
}

function openEpisodeStatuses() {
  document.getElementById('episodeStatuses').showModal()
}

const deleteShowEpisode = async () => {

  // episode is being deleted


  const confirmation = confirm('Are you sure you want to delete this show episode?');

  if (confirmation) {
    try {
      showStore.episodeIsBeingDeleted = props.episode.id;
      // Make the DELETE request to delete the show episode
      await axios.delete(`/shows/${props.showSlug}/episode/${props.episode.slug}`)
          .then((response) => {
            // Handle the response
            if (response.status === 200) {
              // Display the JSON message from the response
              showStore.errorMessage = response.data.message
              // alert(response.data.message);
              // Update the UI
              Inertia.reload()
              showStore.episodeIsBeingDeleted = 0;
              // For example, you can use Inertia's visit method to navigate to a new page:
              // await Inertia.visit(route('some.route'));
            } else {
              // Handle other response statuses if needed
              showStore.errorMessage = response.statusText
              console.error('Delete request failed:', response.statusText);
              showStore.episodeIsBeingDeleted = 0;
              Inertia.reload()
            }
          })
          .catch((error) => {
            console.error('Error deleting show episode:', error);
            showStore.errorMessage = error
            showStore.episodeIsBeingDeleted = 0;
            Inertia.reload()
          });


      // await Inertia.delete(route('shows.showEpisodes.destroy', [props.showSlug, props.episode.slug]));
      console.log("it should've been deleted");
      // Redirect to a different page or update the UI as needed

      console.log('inertia reload');
      // For example, you can use Inertia's visit method to navigate to a new page:
      // await Inertia.visit(route('some.route'));
    } catch (error) {
      console.error('Error deleting show episode:', error);
    }
  }
};


</script>
